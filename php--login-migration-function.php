# migration function php
```
<?php
class Login extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->helper('cookie');
	}

	function index(){
		date_default_timezone_set('Asia/Jakarta');
		$this->session->unset_userdata('pwdexpire_username');
		$this->session->unset_userdata('pwdexpire_userrole');
		$nik = $this->session->userdata('bop_username');		//null if not login
		$data['username_session'] = $nik;
		$data['current_page'] = "";
		$data['flash_message'] = $this->session->flashdata('alert');
		if(!empty($nik)){
			redirect('app/home');
		}else{
			$this->load->view('app/login_template',$data);
		}
	}

	function check_login(){
		$nik = $this->input->post('username');
		$password = $this->input->post('password');
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://hrd.citratubindo.com/sys-hr/sys-login',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS =>'{"username":"'.$nik.'","password":"'.$password.'"}',
		CURLOPT_HTTPHEADER => array(
			'Authorization: '.sso_auth.'',
			'User-Agent: '.sso_user.'',
			'Content-Type: text/plain'
		),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$arr = json_decode($response);
		if($arr->loginCodes=='success'){
			$res_arr = $arr->details;
			if(empty($res_arr[0]->_nik)){
				$this->session->set_flashdata('alert', 'Account invalid please contact administrator');
				redirect('app/login'); exit;
			}
			 $res_arr[0]->_nik;
			 $this->login_accept($res_arr);
		}else{
			$this->session->set_flashdata('alert', $arr->details);
			redirect('app/login');
		}
		//  check if employee allowed
		// if($res_arr[0]->_contract_type=='Employee'){
		// 	$this->login_accept($res_arr);
		// }else{
		// 	$this->session->set_flashdata('eror', 'eror');
		// 	redirect('app/login');
		// }
	}



function login_accept($res_arr){
	$nik = $res_arr[0]->_nik;
	$name = $res_arr[0]->_name;
	$gid = $res_arr[0]->_gid;
	if($res_arr[0]->_contract_type!=='Employee'){
		$this->session->set_flashdata('alert', 'You dont have access for this application');
		redirect('app/login'); exit;
	}
	$userinfo = $this->Madminuser->get_user_info_bynik($nik);
		$level_data = '0';
			foreach($userinfo as $item){
				$nik = $item->nik;
				$level_data = $item->level;
				$role = $item->role;
			}
			$name_telegram = str_replace(" ","_","$name");
			$telegram = 'msg#'.$name_telegram.'_'.$nik.'#';
			$time = time();
			$usersession = array(
				'bop_username'  => $res_arr[0]->_nik,
				'bop_level' => $level_data,
				'bop_gid'  => $res_arr[0]->_gid,
				'bop_empid'  => $res_arr[0]->_emp_id,
				'bop_name'  => $res_arr[0]->_name,
				'bop_dept'  => $res_arr[0]->_dept,
				'bop_jab'  => $res_arr[0]->_jab,
				'bop_role'  => $role,
				'bop_telegram'  => $telegram,
				'bop_telegram'  => $res_arr[0]->_login_as,
			);
			$this->session->set_userdata($usersession);
			$priviledge = [];
			$data_priv = $this->Mmaster->get_role();
			foreach($data_priv as $dt){
				$idrole = $dt->idrole;
				$priviledge[$idrole] = $this->Mmaster->get_priviledge($gid,$idrole);
				$priviledge[$idrole.'_1'] = $this->Mmaster->get_priviledge_data($gid,$idrole,"1");
				$priviledge[$idrole.'_2'] = $this->Mmaster->get_priviledge_data($gid,$idrole,"2");
			}
      		$this->session->set_userdata(['priviledge_bop' => $priviledge]);
			$this->session->set_flashdata('successlogin', 'welcome');
			redirect('app/home');
}

	function logout(){
		date_default_timezone_set('Asia/Jakarta');
		$this->session->unset_userdata('bop_username');
		$this->session->unset_userdata('bop_level');
		$this->session->unset_userdata('bop_gid');
		$this->session->unset_userdata('bop_empid');
		$this->session->unset_userdata('bop_name');
		$this->session->unset_userdata('bop_dept');
		$this->session->unset_userdata('bop_jab');
		$this->session->unset_userdata('bop_role');
		$this->session->unset_userdata('bop_telegram');
		redirect('app/login');
	}

}
/* End of file upload.php */
/* Location: ./system/application/controllers/admin/login.php */


```

```
define('sso_auth', 'BoP2023!');
define('sso_user', 'bop.citratubindo.com');
```