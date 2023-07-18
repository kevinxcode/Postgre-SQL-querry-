# Postgre-SQL-querry

# update timestamp when update execute
```
-- FUNCTION: public.update_updated_on_user_task()

-- DROP FUNCTION IF EXISTS public.update_updated_on_user_task();

CREATE OR REPLACE FUNCTION public.update_updated_on_user_task()
    RETURNS trigger
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE NOT LEAKPROOF
AS $BODY$
BEGIN
    NEW.updated_on = now();
    RETURN NEW;
END;
$BODY$;

ALTER FUNCTION public.update_updated_on_user_task()
    OWNER TO postgres;
```
