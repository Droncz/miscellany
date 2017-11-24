<?php

namespace App\Http\Controllers;

use App\Character;
use App\Organisation;
use App\Models\OrganisationMember;
use App\Http\Requests\StoreCharacter;
use App\Http\Requests\StoreOrganisationMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OrganisationMemberController extends Controller
{
    /**
     * @var string
     */
    protected $view = 'organisations.members';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('campaign.member');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Organisation $organisation)
    {
        $this->authorize('create', OrganisationMember::class);
        return view($this->view . '.create', [
            'model' => $organisation
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrganisationMember $request, Organisation $organisation)
    {
        $this->authorize('create', OrganisationMember::class);

        $relation = OrganisationMember::create($request->all());
        return redirect()->route('organisations.show', [$organisation->id, 'tab' => 'member'])
            ->with('success', trans($this->view . '.create.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Character  $character
     * @return \Illuminate\Http\Response
     */
    public function show(Organisation $organisation, OrganisationMember $organisationMember)
    {
        $this->authorize('view', $organisationMember);

        return view($this->view . '.show', [
            'model' => $organisation,
            'member' => $organisationMember
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Character  $character
     * @return \Illuminate\Http\Response
     */
    public function edit(Organisation $organisation, OrganisationMember $organisationMember)
    {
        $this->authorize('update', $organisationMember);

        return view($this->view . '.edit', [
            'model' => $organisation,
            'member' => $organisationMember
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Character  $character
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOrganisationMember $request, Organisation $organisation, OrganisationMember $organisationMember)
    {
        $this->authorize('update', $organisationMember);

        $organisationMember->update($request->all());
        return redirect()->route('organisations.show', [$organisation->id, 'tab' => 'member'])
            ->with('success', trans($this->view . '.edit.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OrganisationMember  $organisationMember
     * @return \Illuminate\Http\Response
     */
    public function destroy(Organisation $organisation, OrganisationMember $organisationMember)
    {
        $this->authorize('delete', $organisationMember);

        $organisationMember->delete();
        return redirect()->route('organisations.show', [$organisationMember->organisation_id, 'tab' => 'member'])
            ->with('success', trans($this->view . '.destroy.success'));
    }
}
