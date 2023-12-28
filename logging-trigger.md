CREATE TRIGGER save_log
BEFORE UPDATE OR DELETE ON 
    FOR EACH ROW EXECUTE PROCEDURE workplaze.save_log();

--------

-- FUNCTION: workplaze.save_log()

-- DROP FUNCTION IF EXISTS workplaze.save_log();

CREATE OR REPLACE FUNCTION workplaze.save_log()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE NOT LEAKPROOF SECURITY DEFINER
AS $BODY$
       BEGIN
         IF TG_OP = 'INSERT'
         THEN 
           RETURN NEW;
         ELSIF  TG_OP = 'UPDATE'
         THEN
           INSERT INTO workplaze.t_history (
             tabname, schemaname, operation, new_val, old_val
           )
           VALUES (TG_RELNAME, TG_TABLE_SCHEMA, TG_OP, row_to_json(NEW), row_to_json(OLD));
           RETURN NEW;
         ELSIF TG_OP = 'DELETE'
         THEN
           INSERT INTO workplaze.t_history
             (tabname, schemaname, operation, old_val)
             VALUES (
               TG_RELNAME, TG_TABLE_SCHEMA, TG_OP, row_to_json(OLD)
             );
             RETURN OLD;
         END IF;
       END;
$BODY$;

ALTER FUNCTION workplaze.save_log()
    OWNER TO postgres;



