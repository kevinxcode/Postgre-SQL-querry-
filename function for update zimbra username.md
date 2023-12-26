
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
		select _gid,_name,_nik from workplaze.employee_active_filter(current_date, '', 'ALL', 'ALL', 'ALL', Null, 0)
		--     
	LOOP
	IF EXISTS (select global_id from workplaze.account where global_id = sql_cur._gid and account_type='zimbra')  THEN
		RAISE NOTICE 'update ';
		UPDATE workplaze.account
	SET fullname=sql_cur._name, username=sql_cur._nik
	WHERE global_id=sql_cur._gid and account_type='zimbra' ;
	ELSE
		RAISE NOTICE 'insert ';
		INSERT INTO workplaze.account(
	global_id, fullname, username, account_type)
	VALUES (sql_cur._gid, sql_cur._name, sql_cur._nik, 'zimbra');
	END IF;
		
		
	_counter := _counter + 1;
	RAISE NOTICE 'The counter is %', sql_cur._gid;
  END LOOP;
end;
$$
