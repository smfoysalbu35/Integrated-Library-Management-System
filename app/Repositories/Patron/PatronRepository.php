<?php

namespace App\Repositories\Patron;

use Illuminate\Http\Request;
use App\Models\Patron;
use App\Repositories\Patron\PatronRepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class PatronRepository implements PatronRepositoryInterface
{
    protected $patron;

    public function __construct(Patron $patron)
    {
        $this->patron = $patron;
    }

    public function get(string $order = 'id', string $sort = 'ASC')
    {
        try {
            $patrons = $this->patron->orderBy($order, $sort)->get();
            return $patrons;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function paginate(int $perPage = 10, string $order = 'id', string $sort = 'ASC')
    {
        try {
            $patrons = $this->patron->orderBy($order, $sort)->paginate($perPage);
            return $patrons;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getBy(string $field, string $value)
    {
        try {
            $patrons = $this->patron->where($field, $value)->get();
            return $patrons;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function createPatronNo()
    {
        try {
            $patron = $this->patron->selectRaw("
                    if(
                        (COUNT(*) + 1) >= 1 && (COUNT(*) + 1) <= 9,
                        CONCAT('PAT-', CURRENT_DATE(), '-000', COUNT(*) + 1),
                        if(
                            (COUNT(*) + 1) >= 10 && (COUNT(*) + 1) <= 99,
                            CONCAT('PAT-', CURRENT_DATE(), '-00', COUNT(*) + 1),
                            if(
                                (COUNT(*) + 1) >= 100 && (COUNT(*) + 1) <= 999,
                                CONCAT('PAT-', CURRENT_DATE(), '-0', COUNT(*) + 1),
                                CONCAT('PAT-', CURRENT_DATE(), '-', COUNT(*) + 1)
                            )
                        )
                    ) as patron_no
                ")->firstOrFail();

            return $patron->patron_no;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function create(Request $request)
    {
        try {
            $patron = new Patron;
            $patron->patron_no = $request->patron_no;
            $patron->last_name = $request->last_name;
            $patron->first_name = $request->first_name;

            if($request->has('middle_name') && $request->get('middle_name'))
                $patron->middle_name = $request->middle_name;

            $patron->house_no = $request->house_no;
            $patron->street = $request->street;
            $patron->barangay = $request->barangay;
            $patron->municipality = $request->municipality;
            $patron->province = $request->province;

            $patron->contact_no = $request->contact_no;
            $patron->patron_type_id = $request->patron_type_id;
            $patron->section_id = $request->section_id;

            if($request->hasFile('image'))
            {
                $image = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('public/patron/', $image);
                $patron->image = '/storage/patron/' . $image;
            }else {
                $patron->image = '/assets/images/no-avatar.jpg';
            }

            $patron->save();
            return $patron;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function find(int $id)
    {
        try {
            $patron = $this->patron->findOrFail($id);
            return $patron;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function findBy(string $field, string $value)
    {
        try {
            $patron = $this->patron->where($field, $value)->firstOrFail();
            return $patron;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        try {
            $patron = $this->patron->findOrFail($id);
            $patron->patron_no = $request->patron_no;
            $patron->last_name = $request->last_name;
            $patron->first_name = $request->first_name;

            if($request->has('middle_name') && $request->get('middle_name'))
                $patron->middle_name = $request->middle_name;

            $patron->house_no = $request->house_no;
            $patron->street = $request->street;
            $patron->barangay = $request->barangay;
            $patron->municipality = $request->municipality;
            $patron->province = $request->province;

            $patron->contact_no = $request->contact_no;
            $patron->patron_type_id = $request->patron_type_id;
            $patron->section_id = $request->section_id;

            if($request->hasFile('image'))
            {
                $image = time() . '.' . $request->file('image')->getClientOriginalExtension();
                $request->file('image')->storeAs('public/patron/', $image);
                $patron->image = '/storage/patron/' . $image;
            }

            $patron->save();
            return $patron;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $patron = $this->patron->findOrFail($id);
            $patron->delete();

            return $patron;
        }catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }
    }

    public function count()
    {
        try {
            $count = $this->patron->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function countBy(string $field, string $value)
    {
        try {
            $count = $this->patron->where($field, $value)->count();
            return $count;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getTopLibraryUserReport(array $data)
    {
        try {
            $topLibraryUsers = $this->patron->select('id', 'patron_no', 'last_name', 'first_name', 'middle_name', 'patron_type_id', 'section_id')
                ->selectRaw("(SELECT COUNT(*) FROM patron_attendance_logs WHERE patron_attendance_logs.patron_id = patrons.id
                    AND patron_attendance_logs.date_in >= '".$data['from']."'
                    AND patron_attendance_logs.date_in <= '".$data['to']."'
                    AND patron_attendance_logs.status = 0) as no_of_attendance")

                ->with('patron_type:id,name')
                ->with('section:id,name')

                ->where(['patron_type_id' => $data['patron_type_id']])
                ->whereRaw("(SELECT COUNT(*) FROM patron_attendance_logs WHERE patron_attendance_logs.patron_id = patrons.id
                    AND patron_attendance_logs.date_in >= '".$data['from']."'
                    AND patron_attendance_logs.date_in <= '".$data['to']."'
                    AND patron_attendance_logs.status = 0) > 0")

                ->orderBy('no_of_attendance', 'DESC')
                ->limit(10)
                ->get();

            return $topLibraryUsers;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    public function getTopBorrowerReport(array $data)
    {
        try {
            $topBorrowers = $this->patron->select('id', 'patron_no', 'last_name', 'first_name', 'middle_name', 'patron_type_id', 'section_id')
                ->selectRaw("(SELECT COUNT(*) FROM borrows WHERE borrows.patron_id = patrons.id
                    AND borrows.borrow_date >= '".$data['from']."'
                    AND borrows.borrow_date <= '".$data['to']."') as no_of_borrow")

                ->with('patron_type:id,name')
                ->with('section:id,name')

                ->where(['patron_type_id' => $data['patron_type_id']])
                ->whereRaw("(SELECT COUNT(*) FROM borrows WHERE borrows.patron_id = patrons.id
                    AND borrows.borrow_date >= '".$data['from']."'
                    AND borrows.borrow_date <= '".$data['to']."') > 0")

                ->orderBy('no_of_borrow', 'DESC')
                ->limit(10)
                ->get();

            return $topBorrowers;
        }catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }
}
