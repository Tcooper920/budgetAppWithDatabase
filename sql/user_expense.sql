CREATE TABLE IF NOT EXISTS user_expenses (
    expense_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    expense_name VARCHAR(20) NOT NULL,
    expense_amount INT UNSIGNED NOT NULL,
    expense_type TEXT NOT NULL,
    PRIMARY KEY (expense_id)
);