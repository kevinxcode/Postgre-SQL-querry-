# Postgre-SQL-querry

# list all trigger 
```
SELECT trigger_name, event_manipulation, event_object_table, action_statement 
FROM information_schema.triggers;
```

# update timestamp when update execute - workplaze approval table
```
-- FUNCTION: workplaze.update_tmstime_approval_struck()

-- DROP FUNCTION IF EXISTS workplaze.update_tmstime_approval_struck();

CREATE OR REPLACE FUNCTION workplaze.update_tmstime_approval_struck()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE NOT LEAKPROOF
AS $BODY$
BEGIN
    NEW.timestamp = now();
    RETURN NEW;
END;
$BODY$;

ALTER FUNCTION workplaze.update_tmstime_approval_struck()
    OWNER TO postgres;


CREATE TRIGGER update_tmstime_approval_struck_on
    BEFORE UPDATE
    ON
        workplaze.approval
    FOR EACH ROW
EXECUTE PROCEDURE workplaze.update_tmstime_approval_struck();
```

# qbik date time 
```
-- FUNCTION: workplaze.update_tmstime_qbik()

-- DROP FUNCTION IF EXISTS workplaze.update_tmstime_qbik();

CREATE OR REPLACE FUNCTION workplaze.update_tmstime_qbik()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE NOT LEAKPROOF
AS $BODY$
BEGIN
    NEW.created_at = now();
    RETURN NEW;
END;
$BODY$;

ALTER FUNCTION workplaze.update_tmstime_qbik()
    OWNER TO postgres;
	

CREATE TRIGGER update_tmstime_qbik_on
    BEFORE UPDATE
    ON
        workplaze.qbik
    FOR EACH ROW
EXECUTE PROCEDURE workplaze.update_tmstime_qbik();
```
