<?php

/*
 * This file is part of the Goodreads package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Poposki\Goodreads\Server;

use League\OAuth1\Client\Credentials\CredentialsException;

/**
 * @author Darko Poposki <poposki.darko@gmail.com>
 */
trait MethodsTrait
{
    /**
     * @param string $path
     * @param array $parameters
     * @param bool $authenticate
     *
     * @return array
     * @throws CredentialsException
     */
    abstract public function get($path, $parameters = [], $authenticate = false);

    /**
     * @param string $path
     * @param array $parameters
     * @param bool $authenticate
     *
     * @return array
     * @throws CredentialsException
     */
    abstract public function post($path, $parameters = [], $authenticate = false);

    /**
     * @param string $path
     * @param array $parameters
     * @param bool $authenticate
     *
     * @return array
     * @throws CredentialsException
     */
    abstract public function put($path, $parameters = [], $authenticate = false);

    /**
     * @param string $path
     * @param array $parameters
     * @param bool $authenticate
     *
     * @return array
     * @throws CredentialsException
     */
    abstract public function delete($path, $parameters = [], $authenticate = false);

    /**
     * Get id of user who authorized OAuth
     * https://www.goodreads.com/api/index#auth.user
     *
     * @return array
     */
    public function getUserId()
    {
        return $this->get(
            'api/auth_user',
            [],
            true
        );
    }

    /**
     * Paginate an author's books
     * https://www.goodreads.com/api/index#author.books
     *
     * @param int|string $authorId
     * @param int $pageId
     * @return array
     */
    public function getBooksByAuthorId($authorId, $pageId = 1)
    {
        return $this->get(
            'author/list',
            [
                'id' => $authorId,
                'page' => $pageId,
            ]
        );
    }

    /**
     * Get info about an author by id
     * https://www.goodreads.com/api/index#author.show
     *
     * @param int|string $authorId
     * @return array
     */
    public function getAuthorById($authorId)
    {
        return $this->get(
            sprintf('author/show/%s', $authorId)
        );
    }

    /**
     * Follow an author
     * https://www.goodreads.com/api/index#author_following.create
     *
     * @param int|string $authorId
     * @return array
     */
    public function followAuthorById($authorId)
    {
        return $this->post(
            sprintf('author_followings', $authorId),
            [
                'id' => $authorId,
            ],
            true
        );
    }

    /**
     * Unfollow an author
     * https://www.goodreads.com/api/index#author_following.destroy
     *
     * @param int|string $authorFollowingId
     * @return array
     */
    public function unfollowAuthorByFollowingId($authorFollowingId)
    {
        return $this->delete(
            sprintf('author_followings/%s', $authorFollowingId),
            [],
            true
        );
    }

    /**
     * Show author following information
     * https://www.goodreads.com/api/index#author_following.show
     *
     * @param int|string $authorFollowingId
     * @return array
     */
    public function getAuthorFollowingInfoById($authorFollowingId)
    {
        return $this->get(
            sprintf('author_followings/%s', $authorFollowingId),
            [],
            true
        );
    }

    /**
     * Get Goodreads book IDs given ISBNs
     * https://www.goodreads.com/api/index#book.isbn_to_id
     *
     * @param string $isbn
     * @return array
     */
    public function getBookIdByIsbn($isbn)
    {
        return $this->get(
            'book/isbn_to_id',
            [
                'isbn' => $isbn,
            ]
        );
    }

    /**
     * Get Goodreads work IDs given Goodreads book IDs
     * https://www.goodreads.com/api/index#book.id_to_work_id
     *
     * @param string $bookId
     * @return array
     */
    public function getWorkIdByBookId($bookId)
    {
        return $this->get(
            'book/id_to_work_id',
            [
                'id' => $bookId,
            ]
        );
    }

    /**
     * Get review statistics given a list of ISBNs
     * https://www.goodreads.com/api/index#book.review_counts
     *
     * @param string $isbn
     * @return array
     */
    public function getReviewStatisticsByIsbn($isbn)
    {
        return $this->get(
            'book/review_counts',
            [
                'isbns' => $isbn,
                'format' => 'json',
            ]
        );
    }

    /**
     * Get the reviews for a book given a Goodreads book id
     * https://www.goodreads.com/api/index#book.show
     *
     * @param int|string $bookId
     * @param bool $textOnly
     * @param bool $rating
     * @return array
     */
    public function getBookReviewsById($bookId, $textOnly = false, $rating = false)
    {
        return $this->get(
            'book/show',
            [
                'id' => $bookId,
                'text_only' => $textOnly,
                'rating' => $rating,
            ]
        );
    }

    /**
     * Get the reviews for a book given an ISBN
     * https://www.goodreads.com/api/index#book.show_by_isbn
     *
     * @param int|string $isbn
     * @param bool $rating
     * @return array
     */
    public function getBookReviewsByIsbn($isbn, $rating = false)
    {
        return $this->get(
            'book/isbn',
            [
                'isbn' => $isbn,
                'rating' => $rating,
            ]
        );
    }

    /**
     * Get the reviews for a book given a title string
     * https://www.goodreads.com/api/index#book.title
     *
     * @param string $title
     * @param string $authorName
     * @param bool $rating
     * @return array
     */
    public function getBookReviewsByTitle($title, $authorName = '', $rating = false)
    {
        return $this->get(
            'book/title',
            [
                'title' => $title,
                'author' => $authorName,
                'rating' => $rating,
            ]
        );
    }

    /**
     * Create a comment
     * https://www.goodreads.com/api/index#comment.create
     *
     * @param string $resourceType
     * @param int|string $resourceId
     * @param string $comment
     * @return array
     */
    public function addComment($resourceType, $resourceId, $comment)
    {
        return $this->post(
            'comment',
            [
                'type' => $resourceType,
                'id' => $resourceId,
                'comment[body]' => $comment,
            ],
            true
        );
    }

    /**
     * List comments on a subject
     * https://www.goodreads.com/api/index#comment.list
     *
     * @param string $type
     * @param int|string $id
     * @param int $pageId
     * @return array
     */
    public function getCommentsByType($type, $id, $pageId = 1)
    {
        return $this->get(
            'comment',
            [
                'type' => $type,
                'id' => $id,
                'page' => $pageId,
            ]
        );
    }

    /**
     * Events in your area
     * https://www.goodreads.com/api/index#events.list
     *
     * @param string $lat
     * @param string $lng
     * @param string $countryCode
     * @param string $postalCode
     * @return array
     */
    public function getEvents($lat = '', $lng = '', $countryCode = '', $postalCode = '')
    {
        return $this->get(
            'event/index',
            [
                'lat' => $lat,
                'lng' => $lng,
                'search' => [
                    'country_code' => $countryCode,
                    'postal_code' => $postalCode,
                ],
            ]
        );
    }

    /**
     * Follow a user
     * https://www.goodreads.com/api/index#followers.create
     *
     * @param int|string $userId
     * @return array
     */
    public function followUserById($userId)
    {
        return $this->post(
            sprintf('user/%s/followers', $userId),
            [],
            true
        );
    }

    /**
     * Unfollow a user
     * https://www.goodreads.com/api/index#followers.destroy
     *
     * @param int|string $userId
     * @return array
     */
    public function unfollowUserById($userId)
    {
        return $this->delete(
            sprintf('user/%s/followers/stop_following', $userId),
            [],
            true
        );
    }

    /**
     * Confirm or decline a friend recommendation
     * https://www.goodreads.com/api/index#friend.confirm_recommendation
     *
     * @param int|string $recommendationId
     * @param string $response
     * @return array
     */
    public function confirmFriendRecommendation($recommendationId, $response = 'Y')
    {
        return $this->post(
            'friend/confirm_recommendation',
            [
                'id' => $recommendationId,
                'response' => $response,
            ],
            true
        );
    }

    /**
     * Confirm or decline a friend request
     * https://www.goodreads.com/api/index#friend.confirm_request
     *
     * @param int|string $requestId
     * @param string $response
     * @return array
     */
    public function confirmFriendRequest($requestId, $response = 'Y')
    {
        return $this->post(
            'friend/confirm_request',
            [
                'id' => $requestId,
                'response' => $response,
            ],
            true
        );
    }

    /**
     * Get friend requests
     * https://www.goodreads.com/api/index#friend.requests
     *
     * @param int $pageId
     * @return array
     */
    public function getFriendRequests($pageId = 1)
    {
        return $this->get(
            'friend/requests',
            [
                'page' => $pageId,
            ],
            true
        );
    }

    /**
     * Add a friend
     * https://www.goodreads.com/api/index#friends.create
     *
     * @param int|string $userId
     * @return array
     */
    public function addFriendById($userId)
    {
        return $this->post(
            'friend/add_as_friend',
            [
                'id' => $userId,
            ],
            true
        );
    }

    /**
     * Join a group
     * https://www.goodreads.com/api/index#group.join
     *
     * @param int|string $groupId
     * @return array
     */
    public function joinGroupById($groupId)
    {
        return $this->post(
            'group/join',
            [
                'id' => $groupId,
            ],
            true
        );
    }

    /**
     * List groups for a given user
     * https://www.goodreads.com/api/index#group.list
     *
     * @param int|string $userId
     * @param string $sort
     * @return array
     */
    public function getGroupsByUserId($userId, $sort = '')
    {
        return $this->get(
            sprintf('group/list/%s', $userId),
            [
                'sort' => $sort,
            ]
        );
    }

    /**
     * Return members of a particular group
     * https://www.goodreads.com/api/index#group.members
     *
     * @param int|string $groupId
     * @param string $sort
     * @param string $query
     * @param int $pageId
     * @return array
     */
    public function getGroupMembersByGroupId($groupId, $sort = '', $query = '', $pageId = 1)
    {
        return $this->get(
            sprintf('group/members/%s', $groupId),
            [
                'sort' => $sort,
                'q' => $query,
                'page' => $pageId,
            ]
        );
    }

    /**
     * Find a group
     * https://www.goodreads.com/api/index#group.search
     *
     * @param string $query
     * @param int $pageId
     * @return array
     */
    public function getGroupByQuery($query, $pageId = 1)
    {
        return $this->get(
            'group/search',
            [
                'q' => $query,
                'page' => $pageId,
            ]
        );
    }

    /**
     * Get info about a group by id
     * https://www.goodreads.com/api/index#group.show
     *
     * @param int|string $groupId
     * @param string $sort
     * @param string $order
     * @return array
     */
    public function getGroupById($groupId, $sort = '', $order = '')
    {
        return $this->get(
            sprintf('group/show/%s', $groupId),
            [
                'sort' => $sort,
                'order' => $order,
            ]
        );
    }

    /**
     * See the current user's notifications
     * https://www.goodreads.com/api/index#notifications
     *
     * @param int $page
     * @return array
     */
    public function getUserNotifications($page = 1)
    {
        return $this->get(
            'notifications',
            [
                'page' => $page,
            ],
            true
        );
    }

    /**
     * Add to books owned
     * https://www.goodreads.com/api/index#owned_books.create
     *
     * @param int|string $bookId
     * @param int $conditionCode
     * @param string $conditionDescription
     * @param string $purchaseDate
     * @param string $purchaseLocation
     * @param string $uniqueCode
     * @return array
     */
    public function addOwnedBookById(
        $bookId,
        $conditionCode,
        $conditionDescription,
        $purchaseDate,
        $purchaseLocation,
        $uniqueCode
    )
    {
        return $this->post(
            'owned_books',
            [
                'owned_book' => [
                    'book_id' => $bookId,
                    'condition_code' => $conditionCode,
                    'condition_description' => $conditionDescription,
                    'original_purchase_date' => $purchaseDate,
                    'original_purchase_location' => $purchaseLocation,
                    'unique_code' => $uniqueCode,
                ],
            ],
            true
        );
    }

    /**
     * List books owned by a user
     * https://www.goodreads.com/api/index#owned_books.list
     *
     * @param int $pageId
     * @return array
     */
    public function getOwnedBooks($pageId = 1)
    {
        return $this->get(
            'owned_books/user',
            [
                'page' => $pageId,
            ],
            true
        );
    }

    /**
     * Show an owned book
     * https://www.goodreads.com/api/index#owned_books.show
     *
     * @param int|string $ownedBookId
     * @return array
     */
    public function getOwnedBookById($ownedBookId)
    {
        return $this->get(
            sprintf('owned_books/show/%s', $ownedBookId),
            [],
            true
        );
    }

    /**
     * Update an owned book
     * https://www.goodreads.com/api/index#owned_books.update
     *
     * @param int|string $ownedBookId
     * @param int|string $bookId
     * @param string $conditionCode
     * @param string $conditionDescription
     * @param string $purchaseDate
     * @param string $purchaseLocation
     * @param string $uniqueCode
     * @return array
     */
    public function updateOwnedBook(
        $ownedBookId,
        $bookId,
        $conditionCode = '',
        $conditionDescription = '',
        $purchaseDate = '',
        $purchaseLocation = '',
        $uniqueCode = ''
    )
    {
        return $this->put(
            sprintf('owned_books/update/%s', $ownedBookId),
            [
                'owned_book' => [
                    'book_id' => $bookId,
                    'condition_code' => $conditionCode,
                    'condition_description' => $conditionDescription,
                    'original_purchase_date' => $purchaseDate,
                    'original_purchase_location' => $purchaseLocation,
                    'unique_code' => $uniqueCode,
                ],
            ],
            true
        );
    }

    /**
     * Delete an owned book
     * https://www.goodreads.com/api/index#owned_books.destroy
     *
     * @param int|string $ownedBookId
     * @return array
     */
    public function deleteOwnedBook($ownedBookId)
    {
        return $this->post(
            sprintf('owned_books/destroy/%s', $ownedBookId),
            [],
            true
        );
    }

    /**
     * Add a quote
     * https://www.goodreads.com/api/index#quotes.create
     *
     * @param string $quote
     * @param string $authorName
     * @param string $authorId
     * @param string $bookId
     * @param string $tags
     * @param string $isbn
     * @return array
     */
    public function addQuote($quote, $authorName, $authorId = '', $bookId = '', $tags = '', $isbn = '')
    {
        return $this->post(
            'quotes',
            [
                'quote' => [
                    'body' => $quote,
                    'author_name' => $authorName,
                    'author_id' => $authorId,
                    'book_id' => $bookId,
                    'tags' => $tags,
                ],
                'isbn' => $isbn,
            ],
            true
        );
    }

    /**
     * Like a resource
     * https://www.goodreads.com/api/index#rating.create
     *
     * @param int|string $resourceId
     * @param string $resourceType
     * @param int $rating
     * @return array
     */
    public function likeResourceById($resourceId, $resourceType, $rating = 1)
    {
        return $this->post(
            'rating',
            [
                'rating' => [
                    'resource_id' => $resourceId,
                    'resource_type' => $resourceType,
                    'rating' => $rating,
                ],
            ],
            true
        );
    }

    /**
     * Unlike a resource
     * https://www.goodreads.com/api/index#rating.destroy
     *
     * @param int|string $resourceId
     * @return array
     */
    public function unlikeResourceById($resourceId)
    {
        return $this->delete(
            'rating',
            [
                'id' => $resourceId,
            ],
            true
        );
    }

    /**
     * Get a user's read status
     * https://www.goodreads.com/api/index#read_statuses.show
     *
     * @param int|string $statusId
     * @return array
     */
    public function getReadStatusById($statusId)
    {
        return $this->get(
            sprintf('read_statuses/%s', $statusId)
        );
    }

    /**
     * Get a recommendation from a user to another user
     * https://www.goodreads.com/api/index#recommendations.show
     *
     * @param int|string $recommendationId
     * @return array
     */
    public function getRecommendationById($recommendationId)
    {
        return $this->get(
            sprintf('recommendations/%s', $recommendationId),
            [],
            true
        );
    }

    /**
     * Add review
     * https://www.goodreads.com/api/index#review.create
     *
     * @param int|string $bookId
     * @param string $review
     * @param int $rating
     * @param string $readAt
     * @param string $shelf
     * @return array
     */
    public function addBookReview($bookId, $review = '', $rating = 0, $readAt = '', $shelf = '')
    {
        return $this->post(
            'review',
            [
                'book_id' => $bookId,
                'review' => [
                    'review' => $review,
                    'rating' => $rating,
                    'read_at' => $readAt,
                ],
                'shelf' => $shelf,
            ],
            true
        );
    }

    /**
     * Edit a review
     * https://www.goodreads.com/api/index#review.edit
     *
     * @param int|string $reviewId
     * @param string $review
     * @param int $rating
     * @param string $readAt
     * @param string $shelf
     * @param string $finished
     * @return array
     */
    public function editBookReview($reviewId, $review = '', $rating = 0, $readAt = '', $shelf = '', $finished = '')
    {
        return $this->put(
            sprintf('review/%s', $reviewId),
            [
                'review' => [
                    'review' => $review,
                    'rating' => $rating,
                    'read_at' => $readAt,
                ],
                'shelf' => $shelf,
                'finished' => $finished,
            ],
            true
        );
    }

    /**
     * Delete a book review
     * https://www.goodreads.com/api/index#review.destroy
     *
     * @param int|string $reviewId
     * @return array
     */
    public function deleteBookReview($reviewId)
    {
        return $this->delete(
            sprintf('review/destroy/%s', $reviewId),
            [],
            true
        );
    }

    /**
     * Get the books on a members shelf
     * https://www.goodreads.com/api/index#reviews.list
     *
     * @param int|string $userId
     * @param string $shelf
     * @param string $query
     * @param string $sort
     * @param string $order
     * @param int $pageId
     * @param string $perPage
     * @return array
     */
    public function getShelfBooksByUserId(
        $userId,
        $shelf = '',
        $query = '',
        $sort = '',
        $order = '',
        $pageId = 1,
        $perPage = ''
    )
    {
        return $this->get(
            'review/list',
            [
                'id' => $userId,
                'shelf' => $shelf,
                'sort' => $sort,
                'search[query]' => $query,
                'order' => $order,
                'page' => $pageId,
                'per_page' => $perPage,
                'v' => 2,
            ]
        );
    }

    /**
     * Recent reviews from all members
     * https://www.goodreads.com/api/index#review.recent_reviews
     *
     * @return array
     */
    public function getAllRecentReviews()
    {
        return $this->get('review/recent_reviews');
    }

    /**
     * Get a review
     * https://www.goodreads.com/api/index#review.show
     *
     * @param int|string $reviewId
     * @param int $pageId
     * @return array
     */
    public function getReviewById($reviewId, $pageId = 1)
    {
        return $this->get(
            'review/show',
            [
                'id' => $reviewId,
                'page' => $pageId,
            ]
        );
    }

    /**
     * Get a user's review for a given book
     * https://www.goodreads.com/api/index#review.show_by_user_and_book
     *
     * @param int|string $userId
     * @param int|string $bookId
     * @param bool $includeReviewOnWork
     * @return array
     */
    public function getReviewByUserIdAndBookId($userId, $bookId, $includeReviewOnWork = false)
    {
        return $this->get(
            'review/show_by_user_and_book',
            [
                'user_id' => $userId,
                'book_id' => $bookId,
                'include_review_on_work' => $includeReviewOnWork,
            ]
        );
    }

    /**
     * Find an author by name
     * https://www.goodreads.com/api/index#search.authors
     *
     * @param string $authorName
     * @return array
     */
    public function getAuthorByName($authorName)
    {
        return $this->get(
            sprintf('api/author_url/%s', $authorName)
        );
    }

    /**
     *  Find books by title, author, or ISBN
     * https://www.goodreads.com/api/index#search.books
     *
     * @param string $query
     * @param string $field
     * @param int $pageId
     * @return array
     */
    public function getBookByQuery($query, $field = 'all', $pageId = 1)
    {
        return $this->get(
            'search/index',
            [
                'q' => $query,
                'search[field]' => $field,
                'page' => $pageId,
            ]
        );
    }

    /**
     * See a series
     * https://www.goodreads.com/api/index#series.show
     *
     * @param int|string $seriesId
     * @return array
     */
    public function getSeriesById($seriesId)
    {
        return $this->get(
            sprintf('series/show/%s', $seriesId)
        );
    }

    /**
     * See all series by an author
     * https://www.goodreads.com/api/index#series.list
     *
     * @param int|string $authorId
     * @return array
     */
    public function getSeriesByAuthorId($authorId)
    {
        return $this->get(
            'series/list',
            [
                'id' => $authorId,
            ]
        );
    }

    /**
     * See all series a work is in
     * https://www.goodreads.com/api/index#series.work
     *
     * @param int|string $workId
     * @return array
     */
    public function getSeriesByWorkId($workId)
    {
        return $this->get(
            sprintf('work/%s/series', $workId)
        );
    }

    /**
     * Add a book to a shelf
     * https://www.goodreads.com/api/index#shelves.add_to_shelf
     *
     * @param int|string $bookId
     * @param string $shelfName
     * @return array
     */
    public function addBookToShelf($bookId, $shelfName)
    {
        return $this->post(
            'shelf/add_to_shelf',
            [
                'book_id' => $bookId,
                'name' => $shelfName,
            ],
            true
        );
    }

    /**
     * Remove a book from a shelf
     * https://www.goodreads.com/api/index#shelves.add_to_shelf
     *
     * @param int|string $bookId
     * @param string $shelfName
     * @return array
     */
    public function removeBookFromShelf($bookId, $shelfName)
    {
        return $this->post(
            'shelf/add_to_shelf',
            [
                'book_id' => $bookId,
                'name' => $shelfName,
                'a' => 'remove',
            ],
            true
        );
    }

    /**
     * Add books to many shelves
     * https://www.goodreads.com/api/index#shelves.add_books_to_shelves
     *
     * @param string $bookIds
     * @param string $shelfNames
     * @return array
     */
    public function addBooksToShelves($bookIds, $shelfNames)
    {
        return $this->post(
            'shelf/add_books_to_shelves',
            [
                'bookids' => $bookIds,
                'shelves' => $shelfNames,
            ],
            true
        );
    }

    /**
     * Get a user's shelves
     * https://www.goodreads.com/api/index#shelves.list
     *
     * @param int|string $userId
     * @param int $pageId
     * @return array
     */
    public function getShelvesByUserId($userId, $pageId = 1)
    {
        return $this->get(
            'shelf/list',
            [
                'user_id' => $userId,
                'page' => $pageId,
            ]
        );
    }

    /**
     * Create a new topic
     * https://www.goodreads.com/api/index#topic.create
     *
     * @param string $subjectType
     * @param int|string $subjectId
     * @param string $title
     * @param string $description
     * @param string $folderId
     * @param int $questionFlag
     * @param string $updateFeed
     * @param string $digest
     * @return array
     */
    public function addTopic(
        $subjectType,
        $subjectId,
        $title,
        $description,
        $folderId = 'general',
        $questionFlag = 0,
        $updateFeed = '',
        $digest = ''
    )
    {
        return $this->post(
            'topic',
            [
                'topic' => [
                    'subject_type' => $subjectType,
                    'subject_id' => $subjectId,
                    'title' => $title,
                    'body_usertext' => $description,
                    'folder_id' => $folderId,
                    'question_flag' => $questionFlag,
                ],
                'update_feed' => $updateFeed,
                'digest' => $digest,
            ],
            true
        );
    }

    /**
     * Get list of topics in a group's folder
     * https://www.goodreads.com/api/index#topic.group_folder
     *
     * @param int|string $folderId
     * @param int|string $groupId
     * @param string $sort
     * @param string $order
     * @param int $pageId
     * @return array
     */
    public function getTopicsByGroupFolderId($folderId, $groupId, $sort = '', $order = '', $pageId = 1)
    {
        return $this->get(
            sprintf('topic/group_folder/%s', $folderId),
            [
                'group_id' => $groupId,
                'sort' => $sort,
                'order' => $order,
                'page' => $pageId,
            ]
        );
    }

    /**
     * Get info about a topic by id
     * https://www.goodreads.com/api/index#topic.show
     *
     * @param int|string $topicId
     * @return array
     */
    public function getTopicById($topicId)
    {
        return $this->get(
            sprintf('topic/show/%s', $topicId)
        );
    }

    /**
     * Get a list of topics with unread comments
     * https://www.goodreads.com/api/index#topic.unread_group
     *
     * @param int|string $groupId
     * @param string $viewed
     * @param string $sort
     * @param int $pageId
     * @return array
     */
    public function getTopicUnreadCommentsByGroupId($groupId, $viewed = '', $sort = '', $pageId = 1)
    {
        return $this->get(
            sprintf('topic/unread_group/%s', $groupId),
            [
                'viewed' => $viewed,
                'sort' => $sort,
                'page' => $pageId,
            ],
            true
        );
    }

    /**
     * Get your friend updates
     * https://www.goodreads.com/api/index#updates.friends
     *
     * @param string $updateType
     * @param string $updateFilter
     * @param string $updateLimit
     * @return array
     */
    public function getFriendUpdates($updateType = '', $updateFilter = '', $updateLimit = '')
    {
        return $this->get(
            'updates/friends',
            [
                'update' => $updateType,
                'update_filter' => $updateFilter,
                'max_updates' => $updateLimit,
            ],
            true
        );
    }

    /**
     * Add book shelf
     * https://www.goodreads.com/api/index#user_shelves.create
     *
     * @param string $shelfName
     * @param bool $exclusive
     * @param bool $sortable
     * @param bool $featured
     * @return array
     */
    public function addBookShelf($shelfName, $exclusive = false, $sortable = false, $featured = false)
    {
        return $this->post(
            'user_shelves',
            [
                'user_shelf' => [
                    'name' => $shelfName,
                    'exclusive_flag' => $exclusive,
                    'sortable_flag' => $sortable,
                    'featured' => $featured,
                ],
            ],
            true
        );
    }

    /**
     * Edit book shelf
     * https://www.goodreads.com/api/index#user_shelves.update
     *
     * @param int|string $shelfId
     * @param string $shelfName
     * @param bool $exclusive
     * @param bool $sortable
     * @param bool $featured
     * @return array
     */
    public function editBookShelfById($shelfId, $shelfName, $exclusive = false, $sortable = false, $featured = false)
    {
        return $this->put(
            sprintf('user_shelves/%s', $shelfId),
            [
                'user_shelf' => [
                    'name' => $shelfName,
                    'exclusive_flag' => $exclusive,
                    'sortable_flag' => $sortable,
                    'featured' => $featured,
                ],
            ],
            true
        );
    }

    /**
     * Get info about a member by id or username
     * https://www.goodreads.com/api/index#user.show
     *
     * @param int|string $userId
     * @param string $username
     * @return array
     */
    public function getUserById($userId, $username = '')
    {
        return $this->get(
            sprintf('user/show/%s', $userId),
            [
                'username' => $username,
            ]
        );
    }

    /**
     * Compare books with another member
     * https://www.goodreads.com/api/index#user.compare
     *
     * @param int|string $userId
     * @return array
     */
    public function getBookComparisonByUserId($userId)
    {
        return $this->get(
            sprintf('user/compare/%s', $userId),
            [],
            true
        );
    }

    /**
     * Get a user's followers
     * https://www.goodreads.com/api/index#user.followers
     *
     * @param int|string $userId
     * @param int $pageId
     * @return array
     */
    public function getFollowersByUserId($userId, $pageId = 1)
    {
        return $this->get(
            sprintf('user/%s/followers', $userId),
            [
                'page' => $pageId,
            ],
            true
        );
    }

    /**
     * Get people a user is following
     * https://www.goodreads.com/api/index#user.following
     *
     * @param int|string $userId
     * @param int $pageId
     * @return array
     */
    public function getFollowingByUserId($userId, $pageId = 1)
    {
        return $this->get(
            sprintf('user/%s/following', $userId),
            [
                'page' => $pageId,
            ],
            true
        );
    }

    /**
     * Get a user's friends
     * https://www.goodreads.com/api/index#user.friends
     *
     * @param int|string $userId
     * @param string $sort
     * @param int $pageId
     * @return array
     */
    public function getFriendsByUserId($userId, $sort = '', $pageId = 1)
    {
        return $this->get(
            'friend/user',
            [
                'id' => $userId,
                'sort' => $sort,
                'page' => $pageId,
            ],
            true
        );
    }

    /**
     * Add user status update
     * https://www.goodreads.com/api/index#user_status.create
     *
     * @param string $bookId
     * @param string $page
     * @param string $percent
     * @param string $body
     * @return array
     */
    public function addStatusUpdate($bookId = '', $page = '', $percent = '', $body = '')
    {
        return $this->post(
            'user_status',
            [
                'user_status' => [
                    'book_id' => $bookId,
                    'page' => $page,
                    'percent' => $percent,
                    'body' => $body,
                ],
            ],
            true
        );
    }

    /**
     * Delete user status update
     * https://www.goodreads.com/api/index#user_status.destroy
     *
     * @param int|string $statusId
     * @return array
     */
    public function removeStatusUpdateById($statusId)
    {
        return $this->post(
            sprintf('user_status/destroy/%s', $statusId),
            [],
            true
        );
    }

    /**
     * Get a user status
     * https://www.goodreads.com/api/index#user_status.show
     *
     * @param int|string $statusId
     * @return array
     */
    public function getStatusUpdateById($statusId)
    {
        return $this->get(
            sprintf('user_status/show/%s', $statusId)
        );
    }

    /**
     * View user statuses
     * https://www.goodreads.com/api/index#user_status.index
     *
     * @return array
     */
    public function getRecentStatusUpdates()
    {
        return $this->get('user_status/index');
    }

    /**
     * See all editions by work
     * https://www.goodreads.com/api/index#work.editions
     *
     * @param int|string $workId
     * @return array
     */
    public function getEditionsByWorkId($workId)
    {
        return $this->get(
            sprintf('work/editions/%s', $workId)
        );
    }
}
