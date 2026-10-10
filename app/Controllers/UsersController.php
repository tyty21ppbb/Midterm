<?php
namespace App\Controllers;
use App\Models\UserModel;
class UsersController extends BaseController
{
    private UserModel $model;
    public function __construct(){ $this->model=new UserModel(); }
    public function index(){return view('users/index',['users'=>$this->model->orderBy('is_active','DESC')->orderBy('full_name')->findAll()]);}
    public function create(){return view('users/form',['user'=>null]);}
    public function store()
    {
        $rules=$this->rules(true); if(!$this->validate($rules))return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $data=$this->data();$data['password']=password_hash((string)$this->request->getPost('password'),PASSWORD_DEFAULT);$data['avatar']=$this->saveAvatar();
        $this->model->insert($data);return redirect()->to('/users')->with('success','Staff account added.');
    }
    public function edit(int $id){$u=$this->model->find($id);if(!$u)return redirect()->to('/users')->with('error','Staff account not found.');return view('users/form',['user'=>$u]);}
    public function update(int $id)
    {
        $u=$this->model->find($id);if(!$u)return redirect()->to('/users')->with('error','Staff account not found.');
        if(!$this->validate($this->rules(false,$id)))return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $data=$this->data();$pw=(string)$this->request->getPost('password');if($pw!=='')$data['password']=password_hash($pw,PASSWORD_DEFAULT);
        $avatar=$this->saveAvatar();if($avatar){$data['avatar']=$avatar;$this->removeAvatar($u['avatar']);}
        $this->model->update($id,$data);return redirect()->to('/users')->with('success','Staff account updated.');
    }
    public function archive(int $id)
    {if($id===(int)session('user_id'))return redirect()->to('/users')->with('error','You cannot archive your own account.');if(!$this->model->find($id))return redirect()->to('/users')->with('error','Staff account not found.');$this->model->update($id,['is_active'=>0]);return redirect()->to('/users')->with('success','Staff account archived.');}
    private function rules(bool $create,int $id=0):array
    {return ['username'=>'required|max_length[50]|is_unique[users.username,id,'.$id.']','full_name'=>'required|max_length[100]',
        'password'=>$create?'required|min_length[8]':'permit_empty|min_length[8]','avatar'=>'if_exist|max_size[avatar,2048]|is_image[avatar]|ext_in[avatar,jpg,jpeg,png,webp]'];}
    private function data():array{return ['username'=>trim((string)$this->request->getPost('username')),'full_name'=>trim((string)$this->request->getPost('full_name')),'is_active'=>1];}
    private function saveAvatar():?string{$f=$this->request->getFile('avatar');if(!$f||$f->getError()===UPLOAD_ERR_NO_FILE)return null;if(!$f->isValid()||$f->hasMoved())return null;$n=$f->getRandomName();$f->move(FCPATH.'uploads/avatars',$n);return $n;}
    private function removeAvatar(?string $n):void{if($n){$p=FCPATH.'uploads/avatars/'.basename($n);if(is_file($p))unlink($p);}}
}
