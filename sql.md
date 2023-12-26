
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
		select global_id from workplaze.person limit 10
		--     
	LOOP
		for sql_cur_2 in 
		SELECT * FROM dblink(dblink.hrd_server(), 'select * from syshr.sync_employee('||QUOTE_LITERAL(sql_cur.global_id)||')' ) 
			AS insert_table(inserted text)
		LOOP
		END LOOP;
		
	_counter := _counter + 1;
	RAISE NOTICE 'The counter is %', sql_cur.global_id;
  END LOOP;
end;
$$
