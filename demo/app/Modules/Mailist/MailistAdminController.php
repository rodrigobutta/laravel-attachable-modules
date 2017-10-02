<?php
namespace App\Modules\Mailist;


// TODO revisar que use no se usan

use Cache;

use App\Models\Profile;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

use Symfony\Component\HttpFoundation\Response;


use RodrigoButta\Admin\Form;
use RodrigoButta\Admin\Grid;
use RodrigoButta\Admin\Facades\Admin;
use RodrigoButta\Admin\Layout\Content;
use RodrigoButta\Admin\Traits\ResourceDispatcherTrait;

use App\Modules\User\UserModel;


class MailistAdminController extends Controller{

    use ResourceDispatcherTrait;

    public function __construct(){

    }


    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('mailistado');

            $content->body($this->mailist());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {

        // fix reb por resources que no interpretan bien el method del controller
        if($id=="create"){
            return $this->create();
        }

        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('editando');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('creando');

            $content->body($this->form());
        });
    }

    /**
     * Admin init page
     *
     * @return Grid
     */
    protected function mailist()
    {
        return Admin::grid(MailistModel::class, function (Grid $grid) {

            // $grid->id('ID')->sortable();

            $grid->column('name', 'Nombre')->sortable();;

            $grid->description()->editable('textarea');

            $enabled_states = [
                'on'  => ['value' => 1, 'text' => 'YES', 'color' => 'primary'],
                'off' => ['value' => 0, 'text' => 'NO', 'color' => 'default'],
            ];
            $grid->enabled()->switch($enabled_states);

            $grid->users()->display(function ($users) {

                $users = array_map(function ($user) {
                    return "<span class='label label-primary'>{$user['name']}</span>";
                }, $users);

                return join('&nbsp;', $users);
            });

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(MailistModel::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->text('name');
            $form->text('description');

            $enable_states = [
                'on'  => ['value' => 1, 'text' => 'YES', 'color' => 'primary'],
                'off' => ['value' => 0, 'text' => 'NO', 'color' => 'default'],
            ];
            $form->switch("enabled")->states($enable_states);

            $form->multipleSelect('users')->options(UserModel::all()->pluck('name', 'id'));

        });
    }

}
