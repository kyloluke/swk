<?php
namespace App\Scopes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CompanyScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if(Auth::check())
        {
            //ログインしていたら自企業も追加
            $company_id = Auth::user()->company_id;
            $builder->where('company_id', 0)
                ->orWhere('company_id', $company_id)
                ->where('is_delete', 0);
        }
        else
        {
            $builder->where('company_id', 0)
                ->where('is_delete', 0);
        }
    }
}