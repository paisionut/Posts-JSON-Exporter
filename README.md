# WP-JSON-Exporter

**Export to JSON WordPress Plugin**

This WordPress plugin enables the export of all WordPress posts to a JSON file through a REST API endpoint. Simplify data retrieval and integration with other systems using this efficient export tool.

## Features

-   Expose a REST API endpoint for retrieving posts in JSON format.
-   Flexible pagination with support for ordering and specifying the number of items.
-   Customizable data fields included in the JSON output.
-   Automatically generates and updates an export.json file in the `/wp-content/pais-feeds/` directory.

## Installation

1.  Download the ZIP file or clone the repository.
2.  Upload the plugin directory to the `/wp-content/plugins/` directory.
3.  Activate the plugin through the 'Plugins' menu in WordPress.

## Configuration

1.  Once activated, the plugin registers a REST API endpoint: `wp-json/pais-feeds/v1/posts`.
2.  Use the endpoint with parameters like `page`, `order`, and `items` to customize the output.

## Usage

1.  Navigate to the WordPress dashboard.
2.  Access posts in JSON format via the REST API endpoint.

Example Endpoint:

bash

`/wp-json/pais-feeds/v1/posts` 

## Contributing

Feel free to contribute, report issues, or suggest enhancements! Your feedback is valuable.

## Author

-   **[paisionut](https://paisionut.com)**

## Version

-   **1.0.4**
