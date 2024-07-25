<?php
namespace App\Libs\ImportExport\Sheet;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;

use App\Libs\Repository\Finder\CategoryFinder;
use App\Libs\Repository\PermissionGroup;

use App\Models\Category as Model;

class HakAksesListSheet implements FromCollection, WithTitle, ShouldAutoSize
{
    private $filter;
    private $accessControl;
    private $data = [];

    public function __construct($filter = null) {
        $this->filter = $filter;

        $this->data[] = $this->headings();
    }

    public function title(): string
    {
        return 'list';
    }

    public function collection(): Collection
    {
        // Get data using finder
        $finder = new CategoryFinder();
        $finder->setAccessControl($this->accessControl);
        $finder->setGroup('permission_group');

        $finder->setPage('all');
        $paginator = $finder->get();

        foreach($paginator as $x) {
            $row = $this->getModel($x->id);

            $repo = new PermissionGroup($row);
            $list = $repo->toArray();

            if(!empty($list)){
                foreach ($list['detail'] as $val) {
                    $this->data[] = [
                        'HAK_AKSES' => $x->name,
                        'PERMISSION' => $val['name'],
                    ];
                }
            }
        }

        return collect($this->data);
    }

    private function headings() : array
    {
        return [
            'HAK_AKSES',
            'PERMISSION',
        ];
    }

    public function setAccessControl($accessControl)
    {
        $this->accessControl = $accessControl;
    }

    private function getModel($id)
    {
        $row = Model::find($id);
        if(empty($row)){
            throw new NotFoundHttpException('Category tidak ditemukan');
        }

        return $row;
    }
}
