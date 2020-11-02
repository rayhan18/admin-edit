

<!---this fullpage of html formet (5 stepe)
 creat file (login.blade.php) in laravel view folder (1)
-->


<!DOCTYPE html>
<html>
    
<head>
	<title>My Awesome Login Page</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
	<link rel="stylesheet" href="{{asset('AdminCss/login.css')}}"/>
</head>

<body>

<div class="container h-100">
		<div class="d-flex justify-content-center h-100">
			<div class="user_card">
				<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
						<img src="images/selfimg.jpg"style="width:100px,height:100px" class="brand_logo" alt="Logo">
					</div>
				</div>
				<div class="d-flex justify-content-center form_container">
					<form>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" id="username"  class="form-control input_user"  placeholder="username">
						</div>
						<div class="input-group mb-2">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input type="password" id="password" class="form-control input_pass"  placeholder="password">
						</div>
						
							<div class="d-flex justify-content-center mt-3 login_container">
				 	<button type="button" onclick="loginBtn()"  class="btn login_btn">Login</button>
				   </div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript" src="{{asset('AdminJs/axios.min.js')}}"></script>
	<script type="text/javascript">
	
		function loginBtn(){
			const userName=document.getElementById('username').value;
			const password=document.getElementById('password').value;
			axios.post('login',{
				userName:userName,
				password:password
			}).then(respons=>{
				if(respons.data==1){
					window.location.href="/admin"
				}else{
					alert('login fail')
				}
			}).catch(error=>{
				alert(error.message)
			})
		}

	</script>
	
    </body>
</html>
<!-- create controller--(2)>
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class LoginController extends Controller
{
    function loginform(){
        return view('loginform');
    }
    function loginTable(Request $request){
        $userName=$request->userName;
        $password=$request->password;
      $count= User::where('userName',$userName)->where('password', $password)->count();
      if($count==1){
          $request->session()->put('userNameKey',$userName);
          return 1;
      }else{
          return 0;
      }
    }
    function logout(Request $request){
         $request->session()->flush('userNameKey');
         return redirect('loginFrom');
    }
    
}
//<!-- create table- and model user.php-(3)>
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('userName');
            $table->string('password');
            $table->timestamps();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
// create file in midelware (4)
<?php

namespace App\Http\Middleware;

use Closure;

class LoginCheck
{
    
    public function handle($request, Closure $next)
    {
        if($request->session()->has('userNameKey')){
            return $next($request); 
        }else{
            return redirect('loginFrom');
        }
       
    }
}
//route (5)

//login middleware group
Route::group(['middleware'=>['loginCheck']], function(){
        //admin
    Route::prefix('/admin')->namespace('Admin')->group(function(){
        Route::get('/','HomeController@index');
        Route::get('/portfolio','projectController@portfolio');
       // if need onather admin file open put here( u no need prefix delete this function)
		//logout
       Route::get('/logout','loginController@logout');
});


Route::get('/loginFrom','LoginController@loginform');
Route::post('/login','LoginController@loginTable');

// go phpmyadmin
// put your user name and passWord in user table