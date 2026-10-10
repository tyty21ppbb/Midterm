<?php
namespace App\Controllers;
use App\Models\ProductModel;
class ProductsController extends BaseController
{
    private ProductModel $model;
    public function __construct(){ $this->model=new ProductModel(); }
    public function index(){ return view('products/index',['products'=>$this->model->orderBy('is_active','DESC')->orderBy('name')->findAll()]); }
    public function create(){ return view('products/form',['product'=>null]); }
    public function store()
    {
        $rules=['name'=>'required|max_length[100]','price'=>'required|decimal|greater_than_equal_to[0]',
            'stock_quantity'=>'required|integer|greater_than_equal_to[0]','image'=>'if_exist|max_size[image,2048]|is_image[image]|ext_in[image,jpg,jpeg,png,webp]'];
        if (!$this->validate($rules)) return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $data=$this->productData(); $data['image']=$this->saveImage('image','products');
        $this->model->insert($data); return redirect()->to('/products')->with('success','Product added.');
    }
    public function edit(int $id)
    { $p=$this->model->find($id); if(!$p) return redirect()->to('/products')->with('error','Product not found.'); return view('products/form',['product'=>$p]); }
    public function update(int $id)
    {
        $p=$this->model->find($id); if(!$p) return redirect()->to('/products')->with('error','Product not found.');
        $rules=['name'=>'required|max_length[100]','price'=>'required|decimal|greater_than_equal_to[0]',
            'stock_quantity'=>'required|integer|greater_than_equal_to[0]','image'=>'if_exist|max_size[image,2048]|is_image[image]|ext_in[image,jpg,jpeg,png,webp]'];
        if (!$this->validate($rules)) return redirect()->back()->withInput()->with('errors',$this->validator->getErrors());
        $data=$this->productData(); $new=$this->saveImage('image','products');
        if($new){$data['image']=$new;$this->removeUpload($p['image'],'products');}
        $this->model->update($id,$data); return redirect()->to('/products')->with('success','Product updated.');
    }
    public function archive(int $id)
    { if(!$this->model->find($id)) return redirect()->to('/products')->with('error','Product not found.'); $this->model->update($id,['is_active'=>0]); return redirect()->to('/products')->with('success','Product archived.'); }
    private function productData(): array { return ['name'=>trim((string)$this->request->getPost('name')),'price'=>(float)$this->request->getPost('price'),'stock_quantity'=>(int)$this->request->getPost('stock_quantity'),'is_active'=>1]; }
    private function saveImage(string $field,string $folder): ?string
    { $f=$this->request->getFile($field); if(!$f || $f->getError()===UPLOAD_ERR_NO_FILE)return null; if(!$f->isValid()||$f->hasMoved())return null; $name=$f->getRandomName(); $f->move(FCPATH.'uploads/'.$folder,$name); return $name; }
    private function removeUpload(?string $name,string $folder): void
    { if($name){$path=FCPATH.'uploads/'.$folder.'/'.basename($name);if(is_file($path))unlink($path);} }
}
