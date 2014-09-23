<?php namespace Modules\User\Composers;

class SidebarViewComposer
{
    public function compose($view)
    {
        $view->items->put('user', [
            'weight' => 1,
            'request' => $view->prefix,
            'route' => 'dashboard.user.index',
            'icon-class' => 'fa fa-dashboard',
            'title' => 'Users',
        ]);
    }
}