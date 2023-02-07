<?php echo "<?php\n"; ?>

namespace <?php echo $vendor; ?>\<?php echo $package; ?>\Http\Controllers;

use App\Http\Controllers\Controller;
use <?php echo $vendor; ?>\<?php echo $package; ?>\Http\Requests\ResourceRequest;

class ResourceController extends Controller
{
    public function handle(ResourceRequest $request)
    {
        $request->findResourceOrFail()->authorizeToUpdate($request);
        $model = $request->findModelQuery()->firstOrFail();

        return response('', 200);
    }
}
