# Goodreads PHP

### Actions

#### Get id of user who authorized OAuth

```php
$provider->getUserId();
```
#### Paginate an author's books

```php
$provider->getBooksByAuthorId($authorId);
```
#### Get info about an author by id

```php
$provider->getAuthorById($authorId);
```
#### Follow an author

```php
$provider->followAuthorById($authorId);
```
####  Unfollow an author

```php
$provider->unfollowAuthorByFollowingId($authorFollowingId);
```
#### Show author following information

```php
$provider->getAuthorFollowingInfoById($authorFollowingId);
```
#### Get Goodreads book IDs given ISBNs

```php
$provider->getBookIdByIsbn($isbn);
```
#### Get Goodreads work IDs given Goodreads book IDs

```php
$provider->getWorkIdByBookId($bookId);
```
#### Get review statistics given a list of ISBNs

```php
$provider->getReviewStatisticsByIsbn($isbn);
```
#### Get the reviews for a book given a Goodreads book id

```php
$provider->getBookReviewsById($bookId);
```
#### Get the reviews for a book given an ISBN

```php
$provider->getBookReviewsByIsbn($isbn);
```
#### Get the reviews for a book given a title string

```php
$provider->getBookReviewsByTitle($title);
```
#### Create a comment

```php
$provider->addComment($resourceType, $resourceId, $comment);
```
#### List comments on a subject

```php
$provider->getCommentsByType($type, $id);
```
#### Events in your area

```php
$provider->getEvents();
```
#### Follow a user

```php
$provider->followUserById($userId);
```
#### Unfollow a user

```php
$provider->unfollowUserById($userId);
```
#### Confirm or decline a friend recommendation

```php
$provider->confirmFriendRecommendation($recommendationId);
```
#### Confirm or decline a friend request

```php
$provider->confirmFriendRequest($requestId);
```
#### Get friend requests

```php
$provider->getFriendRequests();
```
#### Add a friend

```php
$provider->addFriendById($userId);
```
#### Join a group

```php
$provider->joinGroupById($groupId);
```
#### List groups for a given user

```php
$provider->getGroupsByUserId($userId);
```
#### Return members of a particular group

```php
$provider->getGroupMembersByGroupId($groupId);
```
#### Find a group

```php
$provider->getGroupByQuery($query);
```
#### Get info about a group by id

```php
$provider->getGroupById($groupId);
```
#### See the current user's notifications

```php
$provider->getUserNotifications();
```
#### Add to books owned

```php
$provider->addOwnedBookById($bookId, $conditionCode, $conditionDescription, $purchaseDate, $purchaseLocation, $uniqueCode);
```
#### List books owned by a user

```php
$provider->getOwnedBooks();
```
#### Show an owned book

```php
$provider->getOwnedBookById($ownedBookId);
```
#### Update an owned book

```php
$provider->updateOwnedBook($ownedBookId, $bookId);
```
#### Delete an owned book

```php
$provider->deleteOwnedBook($ownedBookId);
```
#### Add a quote

```php
$provider->addQuote($quote, $authorName);
```
#### Like a resource

```php
$provider->likeResourceById($resourceId, $resourceType);
```
#### Unlike a resource

```php
$provider->unlikeResourceById($resourceId);
```
#### Get a user's read status

```php
$provider->getReadStatusById($statusId);
```
#### Get a recommendation from a user to another user

```php
$provider->getRecommendationById($recommendationId);
```
#### Add review

```php
$provider->addBookReview($bookId);
```
#### Edit a review

```php
$provider->editBookReview($reviewId);
```
#### Delete a book review

```php
$provider->deleteBookReview($reviewId);
```
#### Get the books on a members shelf

```php
$provider->getShelfBooksByUserId($userId);
```
#### Recent reviews from all members
    
```php
$provider->getAllRecentReviews();
```
#### Get a review

```php
$provider->getReviewById($reviewId);
```
#### Get a user's review for a given book

```php
$provider->getReviewByUserIdAndBookId($userId, $bookId);
```
#### Find an author by name

```php
$provider->getAuthorByName($authorName);
```
####  Find books by title, author, or ISBN

```php
$provider->getBookByQuery($query);
```
#### See a series

```php
$provider->getSeriesById($seriesId);
```
#### See all series by an author

```php
$provider->getSeriesByAuthorId($authorId);
```
#### See all series a work is in

```php
$provider->getSeriesByWorkId($workId);
```
#### Add a book to a shelf

```php
$provider->addBookToShelf($bookId, $shelfName);
```
#### Remove a book from a shelf

```php
$provider->removeBookFromShelf($bookId, $shelfName);
```
#### Add books to many shelves

```php
$provider->addBooksToShelves($bookIds, $shelfNames);
```
#### Get a user's shelves

```php
$provider->getShelvesByUserId($userId);
```
#### Create a new topic

```php
$provider->addTopic($subjectType, $subjectId, $title, $description);
```
#### Get list of topics in a group's folder

```php
$provider->getTopicsByGroupFolderId($folderId, $groupId);
```
#### Get info about a topic by id

```php
$provider->getTopicById($topicId);
```
#### Get a list of topics with unread comments

```php
$provider->getTopicUnreadCommentsByGroupId($groupId);
```
#### Get your friend updates

```php
$provider->getFriendUpdates();
```
#### Add book shelf

```php
$provider->addBookShelf($shelfName);
```
#### Edit book shelf

```php
$provider->editBookShelfById($shelfId, $shelfName);
```
#### Get info about a member by id or username

```php
$provider->getUserById($userId);
```
#### Compare books with another member

```php
$provider->getBookComparisonByUserId($userId);
```
#### Get a user's followers

```php
$provider->getFollowersByUserId($userId);
```
#### Get people a user is following

```php
$provider->getFollowingByUserId($userId);
```
#### Get a user's friends

```php
$provider->getFriendsByUserId($userId);
```
#### Add user status update

```php
$provider->addStatusUpdate();
```
#### Delete user status update

```php
$provider->removeStatusUpdateById($statusId);
```
#### Get a user status

```php
$provider->getStatusUpdateById($statusId);
```
#### View user statuses

```php
$provider->getRecentStatusUpdates();
```
#### See all editions by work

```php
$provider->getEditionsByWorkId($workId);
```
