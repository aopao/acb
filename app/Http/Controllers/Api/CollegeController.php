<?php

namespace App\Http\Controllers\Api;

use App\Models\College;
use App\Models\CollegeArticle;
use App\Models\CollegeAttribute;
use App\Models\CollegeHasAttribute;
use Illuminate\Http\Request;

class CollegeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('check.user.comfirm.province', [
            'except' => ['index', 'show', 'attribute', 'articles', 'getArticles'],
        ]);
    }

    public function index(Request $request)
    {
        $diplomas_id = $request->get('diplomas_id', null);
        $is_985 = $request->get('is_985', null);
        $is_211 = $request->get('is_211', null);
        $is_public = $request->get('is_public', null);
        $is_zizhao = $request->get('is_zizhao', null);
        $category_id = $request->get('category_id', null);
        $model = College::with('collegeHasAttribute', 'province', 'collegeCategory')
            ->select(['id', 'name', 'province_id', 'category_id', 'logo', 'since']);
        if ($category_id) {
            $colleges = $model->where('category_id', $category_id)->paginate(15);
        } elseif ($diplomas_id) {
            $colleges = $model->where('diplomas_id', $diplomas_id)->paginate(15);
        } elseif ($is_985) {
            $collegeIds = CollegeHasAttribute::where('college_attributes_id', 2)->pluck('college_id');
            $colleges = $model->whereIn('id', $collegeIds)->paginate(15);
        } elseif ($is_211) {
            $collegeIds = CollegeHasAttribute::where('college_attributes_id', 1)->pluck('college_id');
            $colleges = $model->whereIn('id', $collegeIds)->paginate(15);
        } elseif ($is_public) {
            $collegeIds = CollegeHasAttribute::where('college_attributes_id', 7)->pluck('college_id');
            $colleges = $model->whereIn('id', $collegeIds)->paginate(15);
        } elseif ($is_zizhao) {
            $collegeIds = CollegeHasAttribute::where('college_attributes_id', 9)->pluck('college_id');
            $colleges = $model->whereIn('id', $collegeIds)->paginate(15);
        } else {
            $colleges = $model->paginate(15);
        }

        return $colleges;
    }

    public function show($id)
    {
        $data = College::with(['collegeCategory', 'collegeDiplomas', 'province', 'city', 'collegeHasAttribute', 'collegeDetail'])
            ->find($id);

        return $data;
    }

    public function attribute()
    {
        $data = CollegeAttribute::pluck('name', 'id');

        return $this->response->array($data);
    }

    public function articles($id)
    {
        $data = CollegeArticle::where('college_id', $id)->select('title', 'id', 'created_at')->get();

        return $this->response->array($data);
    }

    public function getArticles($id)
    {
        $data = CollegeArticle::find($id);

        return $this->response->array($data);
    }

    public function scores($id)
    {

    }
}
