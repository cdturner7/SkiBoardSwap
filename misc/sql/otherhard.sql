SELECT fldHardGood, fldBrand, fldModel, fldColor, fldSize, fldGender, fldYear
FROM tblHardGoods 
WHERE fldHardGood 
IN ('Helmet','Goggles')
