<?php

namespace KasperFM\NeoBlizzy\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use KasperFM\NeoBlizzy\Models\NeoBlizzyOAuth2Token;
use KasperFM\NeoBlizzy\NeoBlizzyFacade as NeoBlizzy;

class OAuth2Controller extends Controller
{
    public function sc2Auth(Request $request)
    {
        return NeoBlizzy::make()->sc2Api()->authWithApi();
    }

    public function sc2Redirect(Request $request, $profile)
    {
        return response()->redirectTo(config('neoblizzy.api_sc2_redirect_uri') . '?profile=' . $profile);
    }

    public function wowAuth(Request $request)
    {
        return NeoBlizzy::make()->wowApi()->authWithApi();
    }

    public function wowRedirect(Request $request, $profile)
    {
        return response()->redirectTo(config('neoblizzy.api_wow_redirect_uri') . '?profile=' . $profile);
    }
}
