# update gid with trigger 
```
select * from emp_audit
```

# trigger 
```
CREATE OR REPLACE FUNCTION process_emp_audit() RETURNS TRIGGER AS $emp_audit$
    BEGIN
        --
        -- Create a row in emp_audit to reflect the operation performed on emp,
        -- make use of the special variable TG_OP to work out the operation.
        --
        IF (TG_OP = 'INSERT') THEN
-- run triiigger 
do
$$

declare
     sql_cur record;
	 sql_cur_2 record;
  	_counter int := 0;
	_counter2 int := 0;
begin
    FOR sql_cur IN 
		-- 	
		select operation from emp_audit where userid is null
		--     
	LOOP
		FOR sql_cur_2 in 
			-- 		
			select max(global_id::integer)+1 as generate_gid from workplaze.person 
			-- 		
		LOOP
			UPDATE public.emp_audit
			SET userid=sql_cur_2.generate_gid
			WHERE operation=sql_cur.operation;
			
			_counter2 := _counter2 + 1;
			RAISE NOTICE ' second The counter is %', sql_cur_2.generate_gid;
		END LOOP;
		
	_counter := _counter + 1;
	RAISE NOTICE 'The counter is %', sql_cur.operation;
  END LOOP;
end;
$$;
-- end run
            RETURN NEW;
        END IF;
        RETURN NULL; -- result is ignored since this is an AFTER trigger
    END;
$emp_audit$ LANGUAGE plpgsql;
```
# active trigerr
```
CREATE TRIGGER emp_audit_trigger
AFTER INSERT OR UPDATE OR DELETE ON emp_audit
    FOR EACH ROW EXECUTE PROCEDURE process_emp_audit();
```