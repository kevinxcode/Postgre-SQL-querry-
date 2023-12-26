
--  DROP FUNCTION IF EXISTS syshr.sync_employee(text)

CREATE FUNCTION  syshr.sync_employee(_gid_data text)
RETURNS text 
AS 
$$

declare
     _sql  RECORD;
  	_counter int := 0;
begin
    FOR _sql IN 
-- 	
select * from dblink(employee.dbsys(), 'select _local_id,_gid,_emp_id,_name,_birth_name,_dob,_doj,_gender,_marital,_edu,_company_label,_company_name,_nik,_init_date,_resign_date,_emp_type,
_dept,_dept_short,_jab_code,_jab,_grade,_cost_code,_cost_label,_contract_status, _contract_type, _currency, _email, _n1_gid, _n1_name, _n1_email 
					 from workplaze.employee_profile('||QUOTE_LITERAL(_gid_data)||')' ) 
as newtable(
 	_local_id text,
	 _gid text,
	 _emp_id text,
	 _name text,
	 _birth_name text,
	 _dob date,
	 _doj date,
	 _gender text,
	 _marital text,
	 _edu text,
	 _company_label text,
	 _company_name text,
	 _nik text,
	 _init_date date,
	 _resign_date date,
	 _emp_type text,
	 _dept text,
	 _dept_short text,
	 _jab_code text,
	 _jab text,
	 _grade text,
	 _cost_code text,
	 _cost_label text,
	 _contract_status text,
	 _contract_type text,
	_currency text,
	 _email text,
	 _n1_gid text,
	 _n1_name text,
	 _n1_email text
)
		--     
	LOOP
		
		IF EXISTS (select gid  FROM syshr.person where gid=_sql._gid)  THEN
			RAISE NOTICE 'update ';
			UPDATE syshr.person
	SET gid=_sql._gid, emp_id=_sql._emp_id, name=_sql._name, birth_name=_sql._birth_name, dob=_sql._dob, doj=_sql._doj, gender=_sql._gender, marital=_sql._marital, 
	edu=_sql._edu, company_label=_sql._company_label, company_name=_sql._company_name, nik=_sql._nik, init_date=_sql._init_date, resign_date=_sql._resign_date, 
	emp_type=_sql._emp_type, dept=_sql._dept, dept_short=_sql._dept_short, jab_code=_sql._jab_code, jab=_sql._jab, grade=_sql._grade, 
	cost_code=_sql._cost_code, cost_label=_sql._cost_label, contract_status=_sql._contract_status, 
	contract_type=_sql._contract_type, currency=_sql._currency, email=_sql._email, n1_gid=_sql._n1_gid, n1_name=_sql._n1_name, n1_email=_sql._n1_email, created_at=now()
	WHERE gid=_sql._gid;
		ELSE
			RAISE NOTICE 'insert ';
			INSERT INTO syshr.person SELECT 
	_sql._gid ,
	_sql._emp_id ,
	_sql._name ,
	_sql._birth_name ,
	_sql._dob ,
	_sql._doj ,
	_sql._gender ,
	_sql._marital ,
	_sql._edu ,
	_sql._company_label ,
	_sql._company_name ,
	_sql._nik ,
	_sql._init_date ,
	_sql._resign_date ,
	_sql._emp_type ,
	_sql._dept ,
	_sql._dept_short ,
	_sql._jab_code ,
	_sql._jab ,
	_sql._grade ,
	_sql._cost_code ,
	_sql._cost_label ,
	_sql._contract_status ,
	_sql._contract_type ,
	_sql._currency ,
	_sql._email ,
	_sql._n1_gid ,
	_sql._n1_name ,
	_sql._n1_email,
	now();
	
		END IF;
		
		
	RAISE NOTICE 'The counter is %',_sql;
  END LOOP;
  RETURN now();
end;
$$
LANGUAGE plpgsql
