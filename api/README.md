# Gymaster API

This directory contains API endpoints for the Gymaster application.

## Directories

- `transaction/` - Transaction API endpoints
- `subscription/` - Subscription API endpoints
- `program/` - Program API endpoints
- `seed/` - Database seeding scripts

## Setting Up the Database

To populate your database with sample data:

1. Navigate to `http://localhost/gymaster/api/seed/` in your browser
2. Click "Seed Sample Data" to populate the database with sample members, subscriptions, and transactions
3. Return to Transaction Management to see the data

## Available Endpoints

### Transaction Data

- `GET /api/transaction/get_transactions.php` - Returns all transactions with summary statistics

### Subscription Data

- `GET /api/subscription/get_subscriptions.php` - Returns all subscription data with expiring count
- `GET /api/subscription/get_subscription_options.php` - Returns subscription options for dropdown menus
- `GET /api/subscription/filter_subscriptions.php` - Returns filtered subscription data based on provided parameters:
  - `start_date` - Filter by start date (optional)
  - `end_date` - Filter by end date (optional)
  - `subscription_id` - Filter by subscription ID (optional)
  - `program_id` - Filter by program ID (optional)
  - `member_search` - Search for members by name or email (optional)
- `POST /api/subscription/deactivate_subscription.php` - Deactivates a subscription for a specific member:
  - `member_id` - The ID of the member (required)
  - `sub_id` - The ID of the subscription to deactivate (required)

### Program Data

- `GET /api/program/get_program_options.php` - Returns program options for dropdown menus 