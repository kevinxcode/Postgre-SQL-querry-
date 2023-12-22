-- Trigger: t

-- DROP TRIGGER IF EXISTS t ON workplaze.person;

CREATE TRIGGER t
    BEFORE INSERT OR DELETE OR UPDATE 
    ON workplaze.person
    FOR EACH ROW
    EXECUTE FUNCTION workplaze.change_trigger();

    