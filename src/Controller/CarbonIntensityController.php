<?php

namespace App\Controller;

use Exception;
use Psr\Log\LoggerInterface;
use App\Entity\CarbonIntensity;
use App\Service\CarbonIntensityService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * CarbonIntensityController provides web-based functionalities 
 * to view and analyze carbon intensity data.
 */
class CarbonIntensityController extends AbstractController
{
    /**
     * Service that provides helper methods related to Carbon Intensity.
     */
    private $carbonIntensityService;
    private $logger;

    /**
     * Initialize controller with necessary services.
     *
     * @param CarbonIntensityService $intensityService Service for Carbon Intensity related operations.
     */
    public function __construct(CarbonIntensityService $carbonIntensityService, LoggerInterface $logger)
    {
        $this->carbonIntensityService = $carbonIntensityService;
        $this->logger = $logger;
    }

    /**
     * Display Carbon Intensity data based on user input.
     *
     * @Route("/intensity", name="app_intensity")
     * 
     * @param Request $request  The HTTP request object.
     * @param int     $region   The region identifier (default is 1).
     * @param string  $from     The start date (default is an empty string).
     * @param string  $to       The end date (default is an empty string).
     * @param string  $energy   The type of energy to focus on (default is an empty string).
     * 
     * @return Response Rendered view of the Carbon Intensity data.
     */
    #[Route('/carbon-intensity', name: 'app_intensity')]
    public function showData(Request $request, $region = 1, $from = '', $to = '', $energy = ''): Response
    {
        // Extract user input from the request or use default values
        $selectedRegion = $request->request->get('region', $region);
        $selectedFrom = $request->request->get('from', $from);
        $selectedTo = $request->request->get('to', $to);
        $selectedEnergy = $request->request->get('energy', $energy);

        // Fetch and process the intensity data
        $carbonIntensity = new CarbonIntensity();
        try {
            
            $fetchedData = $carbonIntensity->fetchIntensityData($selectedRegion, $selectedFrom, $selectedTo);
            $data = $carbonIntensity->aggregateDatabyDate($fetchedData['data']);
            $data = $carbonIntensity->filterByEnergyType($data, $selectedEnergy);
            $avg = $carbonIntensity->avgForPeriod($data, $selectedEnergy);

        } catch (Exception $e) {

            $this->addFlash('error', "There was an error fetching the data: " . $e->getMessage());
        }

        // Fetch supporting data for view (e.g., regions and energy types)
        $regions = $this->carbonIntensityService->getRegions();
        $energies = $this->carbonIntensityService->getEnergies();

        // Render the results in a view
        return $this->render('carbon-intensity/index.html.twig', [
            'shortname' => $fetchedData['shortname'] ?? '',
            'avg' => $avg ?? '',
            'data' => $data ?? [],
            'region' => $selectedRegion,
            'regions' => $regions,
            'energies' => $energies,
            'energy' => $selectedEnergy,
        ]);
    }

    #[Route('/getData', name: 'app_get_data')]
    public function getData(Request $request): Response
    {
        $requestData = json_decode($request->getContent(), true);
        $region = $requestData['region'] ?? 1;
        $from = $requestData['from'] ?? '';
        $to = $requestData['to'] ?? '';
        $energy = $requestData['energy'] ?? '';

        // Fetch and process the intensity data
        $carbonIntensity = new CarbonIntensity();
        try {
            
            $fetchedData = $carbonIntensity->fetchIntensityData($region, $from, $to);
            $data = $carbonIntensity->aggregateDatabyDate($fetchedData['data']);
            $data = $carbonIntensity->filterByEnergyType($data, $energy);
            $avg = $carbonIntensity->avgForPeriod($data, $energy);
            $data = ['data' => $data, 'avg' => $avg];

        } catch (Exception $e) {

                  // Use the logger service to log the error.
                $this->logger->error("There was an error fetching the data: " . $e->getMessage());
        }

        // Render the results in a view
        return new Response(json_encode($data), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/regions', name: 'app_get_regions')]
    public function getRegions()
    {
        try {
            
            $regions = $this->carbonIntensityService->getRegions();

        } catch (Exception $e) {

            $this->logger->error("There was an error fetching the data: " . $e->getMessage());
        }

      
        return new Response(json_encode($regions), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/energies', name: 'app_get_energies')]
    public function getEnergies()
    {
        try {
            
            $energies = $this->carbonIntensityService->getEnergies();

        } catch (Exception $e) {

            $this->logger->error("There was an error fetching the data: " . $e->getMessage());
        }

        return new Response(json_encode($energies), Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
