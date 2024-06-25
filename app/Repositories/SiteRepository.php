<?php
namespace App\Repositories;

use App\Models\Site;
use App\Interfaces\ISiteRepository;

class SiteRepository implements ISiteRepository
{
    private Site $site;

    /**
     * Create a new controller instance.
     *
     * @param $site
     *
     * @return void
     */
    public function __construct()
    {
        $this->site = new Site;
    }

    public function save(array $sites)
    {
        $dataCreate = [
            'url' => $sites['url'],
            'status' => $sites['status'],
            'created_by' => $sites['created_by'],
        ];
        return $this->site::create($dataCreate);
    }

    public function update($id, array $sites)
    {
        $dataUpdate = [
            'url' => $sites['url'],
            'status' => $sites['status'],
            'created_by' => $sites['created_by'],
        ];
        if (isset($sites['updated_by'])) {
            $dataUpdate['updated_by'] = $sites['updated_by'];
        }
        if (isset($sites['deleted_by'])) {
            $dataUpdate['deleted_by'] = $sites['deleted_by'];
        }

        return $this->site::where('id', $id)->update($dataUpdate);
    }

    public function delete($id)
    {
        return $this->site->where('id', $id)->delete();
    }

    public function getById($id)
    {
        return $this->site->where('id', $id)->first();
    }

    public function getAll()
    {
        return $this->site->all();
    }

    public function getNameById($id)
    {
        $siteName = $this->site->where('id', $id)->value('url');
        return $siteName;
    }

    public function countAllSites()
    {
        return $this->site->count();
    }

    public function countProductBySite($siteId)
    {
        return $this->site->find($siteId)->products()->distinct()->count('products.id');
    }

    public function getProductWithSite($siteId)
    {
        return $this->site->find($siteId)->product_site()->paginate(10);
    }
}
?>
