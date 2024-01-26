# function loop and tes withour  save function
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
$$
```