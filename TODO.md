# TODO: Add Spoilage Algorithm for Organic Products

## Steps to Complete

- [x] Create migration to add shelf_life_days to produks table
- [x] Run migration
- [x] Update Produk model to include shelf_life_days in fillable and add calculateSpoilageFactor method
- [x] Modify StokController to calculate effective stock considering spoilage
- [x] Update view to display effective stock
- [x] Test the spoilage calculation with sample data
