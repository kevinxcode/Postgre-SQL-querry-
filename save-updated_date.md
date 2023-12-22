CREATE OR REPLACE FUNCTION workplaze.update_date_person() RETURNS TRIGGER AS $emp_audit$
    BEGIN
        --
        -- Create a row in emp_audit to reflect the operation performed on emp,
        -- make use of the special variable TG_OP to work out the operation.
        --
        IF (TG_OP = 'DELETE') THEN
           	INSERT INTO workplaze.updated_date_person(tabname, schemaname, operation, gid)
			VALUES (TG_RELNAME, TG_TABLE_SCHEMA, TG_OP, OLD.global_id);
			
            RETURN OLD;
        ELSIF (TG_OP = 'UPDATE') THEN
            INSERT INTO workplaze.updated_date_person(tabname, schemaname, operation, gid)
			VALUES (TG_RELNAME, TG_TABLE_SCHEMA, TG_OP, NEW.global_id);
			
            RETURN NEW;
        ELSIF (TG_OP = 'INSERT') THEN
           	INSERT INTO workplaze.updated_date_person(tabname, schemaname, operation, gid)
			VALUES (TG_RELNAME, TG_TABLE_SCHEMA, TG_OP, NEW.global_id);
	
            RETURN NEW;
        END IF;
        RETURN NULL; -- result is ignored since this is an AFTER trigger
    END;
$emp_audit$ LANGUAGE plpgsql;


CREATE TRIGGER when_updated_date
AFTER INSERT OR UPDATE OR DELETE ON workplaze.person
    FOR EACH ROW EXECUTE PROCEDURE workplaze.update_date_person();