-- PostgreSQL initialization script for TYPO3
-- Sets up the database with proper encoding and extensions

-- Ensure extensions are installed
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Create additional extensions that might be useful
CREATE EXTENSION IF NOT EXISTS "pg_trgm";

-- Set default search path
ALTER DATABASE typo3_dentist SET search_path TO public;

-- Grant privileges
GRANT ALL PRIVILEGES ON DATABASE typo3_dentist TO typo3;
GRANT ALL PRIVILEGES ON SCHEMA public TO typo3;

-- Comment on database
COMMENT ON DATABASE typo3_dentist IS 'TYPO3 Dentist Directory MVP - Bucharest';

-- NOTE: The main TYPO3 tables will be created by the TYPO3 installer
-- This script provides the initial database setup only

