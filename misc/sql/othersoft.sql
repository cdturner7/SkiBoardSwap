SELECT fldSoftGood, fldBrand, fldModel, fldColor, fldSize, fldGender
FROM tblSoftGoods
WHERE fldSoftGood NOT LIKE 'Gloves' 
AND fldSoftGood NOT LIKE 'Mittens' 
AND fldSoftGood NOT LIKE 'Jacket' 
AND fldSoftGood NOT LIKE 'Pants'
