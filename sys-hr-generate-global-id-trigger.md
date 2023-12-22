# generate gid sys hr 
```
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
		select local_id from workplaze.person where global_id is null
		--     
	LOOP
		FOR sql_cur_2 in 
			-- 		
			select max(global_id::integer)+1 as generate_gid from workplaze.person 
			-- 		
		LOOP
			UPDATE workplaze.person
			SET global_id=sql_cur_2.generate_gid
			WHERE local_id=sql_cur.local_id;
			
			_counter2 := _counter2 + 1;
-- 			RAISE NOTICE ' second The counter is %', sql_cur_2.generate_gid;
		END LOOP;
		
	_counter := _counter + 1;
-- 	RAISE NOTICE 'The counter is %', sql_cur.operation;
  END LOOP;
end;
$$
```
```
CREATE OR REPLACE FUNCTION generate_global_id() RETURNS TRIGGER AS $generate_global_id$
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
		select local_id from workplaze.person where global_id is null
		--     
	LOOP
		FOR sql_cur_2 in 
			-- 		
			select max(global_id::integer)+1 as generate_gid from workplaze.person 
			-- 		
		LOOP
			UPDATE workplaze.person
			SET global_id=sql_cur_2.generate_gid
			WHERE local_id=sql_cur.local_id;
			
			_counter2 := _counter2 + 1;
-- 			RAISE NOTICE ' second The counter is %', sql_cur_2.generate_gid;
		END LOOP;
		
	_counter := _counter + 1;
-- 	RAISE NOTICE 'The counter is %', sql_cur.operation;
  END LOOP;
end;
$$;
-- end run
            RETURN NEW;
        END IF;
        RETURN NULL; -- result is ignored since this is an AFTER trigger
    END;
$generate_global_id$ LANGUAGE plpgsql;
```
```
CREATE TRIGGER generate_global_id_trigger
AFTER INSERT  ON workplaze.person
    FOR EACH ROW EXECUTE PROCEDURE generate_global_id();
```