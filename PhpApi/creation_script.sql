DROP TABLE IF EXISTS purchases;

DROP TABLE IF EXISTS items;

create table items
(
    id       varchar(32) not null
        primary key,
    name     text        null,
    quantity int         null,
    quality  text        null,
    price    double      null
);

create table purchases
(
    id      varchar(32) not null
        primary key,
    item_id varchar(32) not null,
    price   double      null,
    constraint fk_item
        foreign key (item_id) references items (id)
        on delete cascade
);

-- Insert initial seed data. Using INSERT IGNORE to prevent errors on re-runs.
INSERT IGNORE INTO items (id, name, quantity, quality, price) VALUES
('item-1', 'Brinza Jerry', 15, 'VeryGood', 89.99),
('item-2', 'Apples', 42, 'Good', 29.50),
('item-3', 'Bananas', 8, 'VeryGood', 1.00),
('item-4', 'Sugar Plum', 12, 'VeryGood', 4.00),
('item-5', 'Lemon', 7, 'Mediocre', 9.00),
('item-6', 'Milk', 10, 'Rotten', 2.00),
('item-7', 'Biscuits', 3, 'VeryGood', 1.00),
('item-8', 'Chocolate', 1, 'VeryGood', 4.00);
