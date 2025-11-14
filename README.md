# Bidbuy
This is a modern auction platform designed for users to showcase and sell valuable items, collectibles, and personal treasures through timed bidding. Each user can create auctions, set starting prices, define expiration dates, and allow others to place competitive bids.
At the end of each auction, the highest bid is automatically marked as the winning offer, but the seller retains full control — they must manually approve the winning bid if the offered price meets their expectations. This ensures flexibility and seller satisfaction in finalizing transactions.
In addition to the auction system, the app features a built-in real-time chat that allows users to communicate directly. Buyers and sellers can exchange messages, negotiate terms, ask questions, and track message status — including whether a message has been delivered or read.
The application combines secure backend logic, real-time user experience, and a clean **RESTful API**, offering a professional-grade foundation for further development, integration, or deployment.

🔑 ## Key Features
- Auction Creation & Management
Users can create auctions for valuable items, set starting prices, define expiration dates, and manage active listings.
- Competitive Bidding System
Registered users can place bids on active auctions. The highest bid is marked as the winner at the end of the auction, but the seller must manually approve it before the transaction is finalized.
- Real-Time Chat Between Users
Integrated 1-on-1 chat allows buyers and sellers to communicate directly, negotiate terms, and ask questions — all in real time.
- Message Status Tracking
Messages include delivery and read status (, ), with real-time updates via Laravel Echo and Pusher.
- Event Broadcasting
Backend emits  and  events to keep chat interfaces synchronized across users.
- Email Notifications
Users receive email alerts for key events such as new bids, being outbid, or winning an auction — powered by Laravel Mail.
- Secure RESTful API
All routes are protected via Laravel Sanctum, with clean separation of concerns and clear access control.
- Database Integrity & Migrations
Enum-based status logic, rollback-safe migrations, and consistent naming conventions ensure long-term maintainability.
- Automated Testing
PHPUnit tests cover core logic, including bid validation, message access, event broadcasting, and API security.

