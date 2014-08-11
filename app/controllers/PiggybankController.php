<?php

use Firefly\Storage\Account\AccountRepositoryInterface as ARI;
use Firefly\Storage\Piggybank\PiggybankRepositoryInterface as PRI;

/**
 * Class PiggybankController
 */
class PiggybankController extends BaseController
{

    protected $_repository;
    protected $_accounts;

    /**
     * @param PRI $repository
     * @param ARI $accounts
     */
    public function __construct(PRI $repository, ARI $accounts)
    {
        $this->_repository = $repository;
        $this->_accounts = $accounts;
        View::share('menu', 'home');

    }

    /**
     * @return $this
     */
    public function create()
    {
        $accounts = $this->_accounts->getActiveDefaultAsSelectList();

        return View::make('piggybanks.create')->with('accounts', $accounts);
    }

    /**
     * @param Piggybank $piggyBank
     *
     * @return $this
     */
    public function delete(Piggybank $piggyBank)
    {
        return View::make('piggybanks.delete')->with('piggybank', $piggyBank);
    }

    /**
     * @param Piggybank $piggyBank
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Piggybank $piggyBank)
    {
        $piggyBank->delete();
        Session::flash('success', 'Piggy bank deleted.');

        return Redirect::route('piggybanks.index');
    }

    /**
     * @param Piggybank $piggyBank
     *
     * @return $this
     */
    public function edit(Piggybank $piggyBank)
    {
        $accounts = $this->_accounts->getActiveDefaultAsSelectList();

        return View::make('piggybanks.edit')->with('piggybank', $piggyBank)->with('accounts', $accounts);
    }

    /**
     * @return $this
     */
    public function index()
    {
        $count = $this->_repository->count();
        $piggybanks = $this->_repository->get();
        $accounts = [];
        // get accounts:
        foreach ($piggybanks as $piggyBank) {
            $account = $piggyBank->account;
            $piggyBank->pct = round(($piggyBank->amount / $piggyBank->target) * 100, 0) . '%';
            $id = $account->id;
            if (!isset($accounts[$id])) {
                $account->balance = $account->balance();
                $account->left = $account->balance - $piggyBank->amount;
                $account->total = $piggyBank->target;
            } else {
                $account->left -= $piggyBank->amount;
                $account->total += $piggyBank->target;

            }
            $accounts[$id] = $account;
        }


        return View::make('piggybanks.index')->with('count', $count)->with('accounts', $accounts)->with(
            'piggybanks', $piggybanks
        );
    }

    /**
     *
     */
    public function show(Piggybank $piggyBank)
    {
        return View::make('piggybanks.show')->with('piggyBank',$piggyBank);
    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $piggyBank = $this->_repository->store(Input::all());
        if ($piggyBank->validate()) {
            Session::flash('success', 'New piggy bank "' . $piggyBank->name . '" created!');

            if (Input::get('create') == '1') {
                return Redirect::route('piggybanks.create')->withInput();
            }

            return Redirect::route('piggybanks.index');


        } else {
            Session::flash('error', 'Could not save piggy bank: ' . $piggyBank->errors()->first());

            return Redirect::route('piggybanks.create')->withInput();
        }

    }

    /**
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update()
    {

        $piggyBank = $this->_repository->update(Input::all());
        if ($piggyBank->validate()) {
            Session::flash('success', 'Piggy bank "' . $piggyBank->name . '" updated.');

            return Redirect::route('piggybanks.index');
        } else {
            Session::flash('error', 'Could not update piggy bank: ' . $piggyBank->errors()->first());

            return Redirect::route('piggybanks.edit', $piggyBank->id)->withErrors($piggyBank->errors())->withInput();
        }


    }

    /**
     * @param Piggybank $piggybank
     */
    public function updateAmount(Piggybank $piggybank)
    {
        $this->_repository->updateAmount($piggybank, Input::get('amount'));
    }
}