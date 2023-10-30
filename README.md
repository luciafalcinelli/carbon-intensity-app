## Running the Symfony Carbon Intensity App

This documentation provides a step-by-step guide on how to set up and run the provided Symfony Carbon Intensity App.

### 1. Prerequisites:

Before proceeding, ensure that you have the following installed on your local machine:

- PHP 7.4 or higher.
- Composer, a PHP dependency manager.
- Symfony CLI.

### 2. Setting up the Application:

1. **Unzipping the Application Folder**: I If you've received the app as a zipped folder, navigate to the location of the zipped file on your local machine and unzip it. This will extract all the application files into a directory.

2. **Install Dependencies**: Navigate to the root directory of the application and run:

   ```bash
   composer install
   ```

### 3. Running the Application:

1. **Using Symfony CLI**:

   If you have Symfony CLI installed, you can start the application using:

   ```bash
   symfony serve
   ```

2. **Using Web Server**:

   Configure your web server to point to the `public` directory of the application. Access the application using the configured domain or IP address.

### 5. Accessing the Carbon Intensity Data:

1. Once the app is running, visit `http://localhost:8000/carbon-intensity` in your browser. Here `8000` is the default port if you used Symfony CLI; adjust as per your setup.

2. You should see an interface with a form to select Region, Energy Generation, and a date range.

3. Fill in your preferred parameters and hit "View Data".

### 4. Understanding the Code:

- **CarbonIntensity Entity**: Represents the main functionality to fetch, aggregate, filter, and calculate averages for carbon intensity data.
  
- **CarbonIntensityController**: Handles the web request, interacts with the CarbonIntensity entity, and renders the data in a view.

- **Twig Template**: Displays the carbon intensity data and provides a form to fetch data based on specific parameters.

### 5. Running Unit Tests:

Before deploying or making significant changes to your application, it's crucial to ensure that everything is functioning as expected. One way to verify this is by running unit tests. Here's how you can execute them for the Symfony Carbon Intensity App:

1. **Navigate to the Root Directory**:

   If you aren't already in the root directory of your application, navigate there using your terminal or command line.

2. **Execute the PHPUnit Command**:

   Symfony uses PHPUnit for unit testing. Run the tests with:

   ```bash
   ./bin/phpunit
   ```

   If you have globally installed PHPUnit, you can also use:

   ```bash
   phpunit
   ```

### Notes:

- Ensure that `allow_url_fopen` is enabled in your `php.ini` file as the app uses `file_get_contents` to fetch data from an external API.

- The app to use a `CarbonIntensityService`, but its details are not provided. Ensure that this service is correctly implemented and provides methods like `getRegions()` and `getEnergies()`.