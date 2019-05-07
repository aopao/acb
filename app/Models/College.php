<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{

    /**
     * Realtion collegeDetail table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function collegeDetail()
    {
        return $this->hasOne('App\Models\CollegeDetail');
    }

    /**
     * Realtion collegeDetail table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function collegeHasAttribute()
    {
        return $this->hasMany('App\Models\CollegeHasAttribute')->select(['college_id', 'college_attributes_id']);
    }

    /**
     * 关联大学类型
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collegeCategory()
    {
        return $this->belongsTo('App\Models\CollegeCategory', 'category_id')->select(['id', 'name']);
    }

    /**
     * 关联大学层次
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function collegeDiplomas()
    {
        return $this->belongsTo('App\Models\CollegeDiplomas', 'diplomas_id')->select(['id', 'name']);
    }

    /**
     * 关联大学所在省份
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo('App\Models\Province')->select(['id', 'name']);
    }

    /**
     * 关联大学所在城市
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo('App\Models\City')->select(['id', 'name']);
    }
}
