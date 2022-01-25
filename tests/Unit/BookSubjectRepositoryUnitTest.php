<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Subject;
use App\Models\BookSubject;
use App\Repositories\BookSubject\BookSubjectRepository;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Jsonable;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookSubjectRepositoryUnitTest extends TestCase
{
    use WithFaker;

    //Get Book Subject
    public function test_it_can_get_all_the_book_subjects()
    {
        factory(BookSubject::class, 3)->create();

        $repository = new BookSubjectRepository(new BookSubject);
        $bookSubjects = $repository->get();

        $this->assertInstanceOf(Collection::class, $bookSubjects);
    }

    //Paginate Book Subject
    public function test_it_can_paginate_the_book_subjects()
    {
        factory(BookSubject::class, 3)->create();

        $repository = new BookSubjectRepository(new BookSubject);
        $bookSubjects = $repository->paginate();

        $this->assertInstanceOf(Jsonable::class, $bookSubjects);
    }

    //Search Book Subject
    public function test_it_can_search_the_book_subjects()
    {
        $bookSubject = factory(BookSubject::class)->create();

        $repository = new BookSubjectRepository(new BookSubject);
        $bookSubjects = $repository->search($bookSubject->subject->name);

        $this->assertInstanceOf(Collection::class, $bookSubjects);
    }

    //Get Book Subject by field name
    public function test_it_can_get_the_book_subjects_by_field_name()
    {
        $bookSubject = factory(BookSubject::class)->create();

        $repository = new BookSubjectRepository(new BookSubject);
        $bookSubjects = $repository->getBy('subject_id', $bookSubject->subject_id);

        $this->assertInstanceOf(Collection::class, $bookSubjects);
    }

    //Book Subject Data
    public function bookSubject()
    {
        $book = factory(Book::class)->create();
        $subject = factory(Subject::class)->create();

        return [
            'book_id' => $book->id,
            'subject_id' => $subject->id,
        ];
    }

    //Create Book Subject
    public function test_it_can_create_the_book_subject()
    {
        $repository = new BookSubjectRepository(new BookSubject);
        $bookSubject = $repository->create($this->bookSubject());

        $this->assertInstanceOf(BookSubject::class, $bookSubject);
    }

    public function test_it_throws_errors_when_creating_the_book_subject()
    {
        $this->expectException(InvalidArgumentException::class);

        $repository = new BookSubjectRepository(new BookSubject);
        $repository->create([]);
    }

    //Find Book Subject
    public function test_it_can_find_the_book_subject()
    {
        $bookSubject = factory(BookSubject::class)->create();

        $repository = new BookSubjectRepository(new BookSubject);
        $found = $repository->find($bookSubject->id);

        $this->assertInstanceOf(BookSubject::class, $found);
        $this->assertEquals($bookSubject->subject_id, $found->subject_id);
    }

    public function test_it_throws_errors_when_finding_the_book_subject()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BookSubjectRepository(new BookSubject);
        $repository->find($this->faker->randomNumber(9));
    }

    //Find Book Subject by field name
    public function test_it_can_find_the_book_subject_by_field_name()
    {
        $bookSubject = factory(BookSubject::class)->create();

        $repository = new BookSubjectRepository(new BookSubject);
        $found = $repository->findBy('subject_id', $bookSubject->subject_id);

        $this->assertInstanceOf(BookSubject::class, $found);
        $this->assertEquals($bookSubject->subject_id, $found->subject_id);
    }

    public function test_it_throws_errors_when_finding_the_book_subject_by_field_name()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BookSubjectRepository(new BookSubject);
        $repository->findBy('id', $this->faker->randomNumber(9));
    }

    //Update Book Subject
    public function test_it_can_update_the_book_subject()
    {
        $bookSubject = factory(BookSubject::class)->create();

        $repository = new BookSubjectRepository(new BookSubject);
        $updated = $repository->update($data = $this->bookSubject(), $bookSubject->id);

        $this->assertInstanceOf(BookSubject::class, $updated);
        $this->assertEquals($updated->subject_id, $data['subject_id']);
    }

    public function test_it_throws_errors_when_updating_the_book_subject()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BookSubjectRepository(new BookSubject);
        $repository->update([], $this->faker->randomNumber(9));
    }

    //Delete Book Subject
    public function test_it_can_delete_the_book_subject()
    {
        $bookSubject = factory(BookSubject::class)->create();

        $repository = new BookSubjectRepository(new BookSubject);
        $deleted = $repository->delete($bookSubject->id);

        $this->assertInstanceOf(BookSubject::class, $deleted);
        $this->assertEquals($bookSubject->subject_id, $deleted->subject_id);
    }

    public function test_it_throws_errors_when_deleting_the_book_subject()
    {
        $this->expectException(ModelNotFoundException::class);

        $repository = new BookSubjectRepository(new BookSubject);
        $repository->delete($this->faker->randomNumber(9));
    }
}
