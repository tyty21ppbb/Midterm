<?php
namespace App\Controllers;
use App\Models\CustomerModel;
class CustomersController extends BaseController
{
    private CustomerModel $model;
    public function __construct(){ $this->model=new CustomerModel(); }
    public function index(){ return view('customers/index',['customers'=>$this->model->orderBy('is_active','DESC')->orderBy('full_name')->findAll()]); }
    public function create(){ return view('customers/form',['customer'=>null]); }
    public function store()
    {
        if(!$this->validate($this->rules())) return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $this->model->insert($this->data()); return redirect()->to('/customers')->with('success','Customer added.');
    }
    public function edit(int $id){$c=$this->model->find($id);if(!$c)return redirect()->to('/customers')->with('error','Customer not found.');return view('customers/form',['customer'=>$c]);}
    public function update(int $id)
    { if(!$this->model->find($id))return redirect()->to('/customers')->with('error','Customer not found.'); if(!$this->validate($this->rules()))return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());$this->model->update($id,$this->data());return redirect()->to('/customers')->with('success','Customer updated.'); }
    public function archive(int $id){if(!$this->model->find($id))return redirect()->to('/customers')->with('error','Customer not found.');$this->model->update($id,['is_active'=>0]);return redirect()->to('/customers')->with('success','Customer archived.');}
    private function rules():array{return ['full_name'=>'required|max_length[100]','email'=>'required|valid_email|max_length[100]','phone'=>'permit_empty|max_length[20]'];}
    private function data():array{return ['full_name'=>trim((string)$this->request->getPost('full_name')),'email'=>trim((string)$this->request->getPost('email')),'phone'=>trim((string)$this->request->getPost('phone'))?:null,'is_active'=>1];}
}
