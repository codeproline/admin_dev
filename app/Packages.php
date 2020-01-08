<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Packages extends Model
{
    //

    use SoftDeletes;

    protected $table = 'packages' ;

    protected $fillable = ['package','pv','rs','amount','code','level_percent'];

    public static function TopUPAutomatic($user_id){
    	$user_detils = User::find($user_id);
    	$balance = Balance::where('user_id',$user_id)->pluck('balance');
    	$package = self::find($user_detils->package);

    	if($package->amount <= $balance){

    		Balance::where('user_id',$user_id)->decrement('balance',$package->amount);
    		PurchaseHistory::create([
                'user_id'=>$user_id,
    			'package_id'=>$user_detils->package,
    			'count'=>$package->top_count,
    			'total_amount'=>$package->amount,
    			]);
    		 User::where('id',$user_id)->increment('revenue_share',$package->rs);

             RsHistory::create([
                    'user_id'=> $user_id ,
                    'from_id'=> $user_id ,
                    'rs_credit'=> $package->rs ,
                    ]);


    		 /* Check for rank upgrade */

    		 Ranksetting::checkRankupdate($user_id,$user_detils->rank_id);

    		return true;

    	}else{
    		return flase ; 
    	}
    }

    public static function levelCommission($user_id,$package_am){

       $user_arrs=[];
       $results=SELF::gettenupllins($user_id,1,$user_arrs);
          foreach ($results as $key => $upuser) {
              $package=ProfileInfo::where('user_id',$upuser)->value('package');
              $pack=Packages::find($package);
              $level_commission=$package_am*$pack->level_percent*0.01;
                $commision = Commission::create([
                'user_id'        => $upuser,
                'from_id'        => $user_id,
                'total_amount'   => $level_commission,
                'tds'            => 0,
                'service_charge' =>0,
                'payable_amount' => $level_commission,
                'payment_type'   => 'level_commission',
                'payment_status' => 'Yes',
          ]);
          /**
          * updates the userbalance
          */
          User::upadteUserBalance($upuser, $level_commission);
          }

    }

     public static function directReferral($sponsor,$from,$package){
          
          $pack=Packages::find($package);
          $direct_ref=Settings::find(1)->direct_referral;
          $direct_referral=$pack->amount*$direct_ref*0.01;
          $commision = Commission::create([
                'user_id'        => $sponsor,
                'from_id'        => $from,
                'total_amount'   => $direct_referral,
                'tds'            => 0,
                'service_charge' =>0,
                'payable_amount' => $direct_referral,
                'payment_type'   => 'direct_referral',
                'payment_status' => 'Yes',
          ]);
          /**
          * updates the userbalance
          */
          User::upadteUserBalance($sponsor, $direct_referral);

    }


    public static function gettenupllins($upline_users,$level=1,$uplines){
     if ($level > 10) 
        return $uplines;  
   
     $upline=Tree_Table::where('user_id',$upline_users)->where('type','=','yes')->value('placement_id'); 

      if ($upline > 0)
          $uplines[]=$upline;

     if ($upline == 1) 
       
        return $uplines;  
    
     return SELF::gettenupllins($upline,++$level,$uplines);
   }

 
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function profileinfo()
    {
        return $this->belongsTo('App\Profileinfo');
    }

    public function PurchaseHistoryR()
    {
        return $this->hasMany('App\PurchaseHistory', 'package_id', 'id');
    }

   
}