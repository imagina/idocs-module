<?php

namespace Modules\Idocs\Sidebar;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\User\Contracts\Authentication;

class SidebarExtender implements \Maatwebsite\Sidebar\SidebarExtender
{
    /**
     * @var Authentication
     */
    protected $auth;

    /**
     * @param Authentication $auth
     *
     * @internal param Guard $guard
     */
    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param Menu $menu
     *
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('core::sidebar.content'), function (Group $group) {

            $group->item(trans('idocs::common.idocs'), function (Item $item) {
                $item->icon('fa fa-file-text');

                $item->item(trans('idocs::category.list'), function (Item $item) {
                    $item->icon('fa fa-files-o');
                    $item->weight(5);
                    $item->append('crud.idocs.category.create');
                    $item->route('crud.idocs.category.index');
                    $item->authorize(
                        $this->auth->hasAccess('idocs.categories.create')
                    );
                });

                $item->item(trans('idocs::doc.list'), function (Item $item) {
                    $item->icon('fa fa-file-text');
                    $item->weight(5);
                    $item->append('crud.idocs.doc.create');
                    $item->route('crud.idocs.doc.index');
                    $item->authorize(
                        $this->auth->hasAccess('idocs.docs.index')
                    );
                });
                 $item->authorize(
                    $this->auth->hasAccess('idocs.categories.index')
                );

            });


        });

        return $menu;
    }
}
