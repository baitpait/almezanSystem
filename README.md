# Dr. Alaa Talbeshy Medical System

**System Language: English Only** 🇺🇸
**Currency: Israeli Shekel (₪)** 🇮🇱

## Overview

A comprehensive medical management system for Dr. Alaa Talbeshy's clinic, designed to handle patient management, appointments, operations, assessments, and invoicing with full financial tracking.

## Features

- **Patient Management**: Complete patient records with medical history
- **Appointment Scheduling**: Multi-stage appointment management
- **Operation Management**: Pre-op, intra-op, and post-op tracking
- **Assessment Management**: Comprehensive eye assessments
- **Services Management**: Simple service catalog with pricing
- **Invoice Management**: Services-based invoicing with automatic calculations (same-day payment)
- **User Management**: Role-based access control
- **Reporting**: Financial and operational reports

## Technical Specifications

- **Language**: English Only
- **Currency**: Israeli Shekel (₪)
- **Framework**: Laravel 11
- **Database**: MySQL 8.0+
- **Frontend**: Tailwind CSS + Alpine.js
- **Authentication**: Laravel Sanctum
- **File Storage**: Local storage with symbolic links

## Services Table Structure

The services table maintains a simple structure:

- **id**: Primary key
- **name**: Service name (unique)
- **base_price**: Service price in Israeli Shekels (₪)
- **is_active**: Service availability status
- **created_at/updated_at**: Timestamps

## Invoice System Notes

- **Payment Timing**: All payments are processed on the same day as the service
- **Due Dates**: Not applicable - all invoices are due immediately
- **Status**: Default status is "Paid" for all new invoices
- **Services**: Each invoice contains exactly one service

## System Requirements

- PHP 8.2+
- MySQL 8.0+
- Node.js 18+
- Composer
- Git

## Installation

1. Clone the repository
2. Run `composer install`
3. Copy `.env.example` to `.env` and configure your database
4. Run `php artisan key:generate`
5. Run `php artisan migrate --seed`
6. Run `npm install && npm run build`
7. Run `php artisan serve`

## Services Table Structure

The system uses a flexible services table that supports:

- **Name**: Service name (unique)
- **Description**: Optional service description
- **Category**: Service category (e.g., Operations, Consultations)
- **Base Price**: Service price in Israeli Shekels (₪)
- **Active Status**: Enable/disable services

Services are fully expandable and can be added/modified through the admin interface.