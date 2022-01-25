<?php

namespace App\Http\Controllers;

use App\Models\PatronAccount;
use App\Repositories\PatronAccount\PatronAccountRepositoryInterface;

class PatronAccountController extends Controller
{
    protected $patronAccount;

    public function __construct(PatronAccountRepositoryInterface $patronAccount)
    {
        $this->patronAccount = $patronAccount;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view', PatronAccount::class);

        $patronAccounts = $this->patronAccount->get();
        return view('admin.manage-patron.patron-account.list', compact('patronAccounts'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', PatronAccount::class);

        $patronAccount = $this->patronAccount->delete($id);
        return redirect()->route('patron-accounts.index')->with(['message' => 'Patron account is successfully deleted.']);
    }
}
