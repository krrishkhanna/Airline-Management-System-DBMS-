-- =============================================
-- TRIGGER IMPLEMENTATION
-- =============================================

-- First, create a table to store flight status changes
CREATE TABLE flight_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    flight_id INT,
    flight_number VARCHAR(50),
    old_status VARCHAR(50),
    new_status VARCHAR(50),
    change_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    change_user VARCHAR(100)
);

-- Create the trigger for tracking flight status changes
DELIMITER //

CREATE TRIGGER flight_status_change_trigger
AFTER UPDATE ON flights
FOR EACH ROW
BEGIN
    -- Check if status has changed
    IF OLD.Status != NEW.Status THEN
        -- Log the change
        INSERT INTO flight_logs (
            flight_id,
            flight_number,
            old_status,
            new_status,
            change_user
        )
        VALUES (
            NEW.id,
            NEW.Flight,
            OLD.Status,
            NEW.Status,
            CURRENT_USER()
        );
    END IF;
END//

DELIMITER ;

-- =============================================
-- CURSOR IMPLEMENTATION
-- =============================================

DELIMITER //

CREATE PROCEDURE process_airline_statistics()
BEGIN
    -- Declare variables for cursor
    DECLARE done INT DEFAULT FALSE;
    DECLARE airline_name VARCHAR(255);
    DECLARE flight_status VARCHAR(50);
    DECLARE passenger_count INT;
    
    -- Variables for calculations
    DECLARE total_flights INT DEFAULT 0;
    DECLARE ontime_flights INT DEFAULT 0;
    DECLARE total_passengers INT DEFAULT 0;
    
    -- Declare cursor for fetching flight data
    DECLARE flight_cursor CURSOR FOR 
        SELECT Name, Status, Passengers 
        FROM flights 
        ORDER BY Name;
    
    -- Declare continue handler
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    -- Create temporary table for results
    DROP TEMPORARY TABLE IF EXISTS airline_stats;
    CREATE TEMPORARY TABLE airline_stats (
        airline_name VARCHAR(255),
        total_flights INT,
        total_passengers INT,
        avg_passengers DECIMAL(10,2),
        ontime_percentage DECIMAL(5,2)
    );
    
    -- Open cursor
    OPEN flight_cursor;
    
    -- Start processing
    read_loop: LOOP
        -- Fetch the next row
        FETCH flight_cursor INTO airline_name, flight_status, passenger_count;
        
        -- Exit if no more rows
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        -- Update statistics
        SET total_flights = total_flights + 1;
        SET total_passengers = total_passengers + passenger_count;
        
        IF flight_status = 'On Time' THEN
            SET ontime_flights = ontime_flights + 1;
        END IF;
        
        -- Insert or update stats in temporary table
        INSERT INTO airline_stats 
            (airline_name, total_flights, total_passengers, avg_passengers, ontime_percentage)
        VALUES 
            (airline_name, 
             total_flights, 
             total_passengers, 
             total_passengers / total_flights,
             (ontime_flights / total_flights) * 100)
        ON DUPLICATE KEY UPDATE
            total_flights = VALUES(total_flights),
            total_passengers = VALUES(total_passengers),
            avg_passengers = VALUES(avg_passengers),
            ontime_percentage = VALUES(ontime_percentage);
            
    END LOOP;
    
    -- Close cursor
    CLOSE flight_cursor;
    
    -- Select final results
    SELECT * FROM airline_stats ORDER BY total_flights DESC;
    
END//

DELIMITER ;

-- =============================================
-- EXAMPLE USAGE
-- =============================================

-- Execute the stored procedure with cursor
CALL process_airline_statistics();

-- Example of trigger in action
UPDATE flights 
SET Status = 'Delayed' 
WHERE Flight = 'EK451';

-- View logged changes
SELECT * FROM flight_logs 
ORDER BY change_timestamp DESC 
LIMIT 5; 