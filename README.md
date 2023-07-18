# Postgre-SQL-querry

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
