-- select SLCustomerAccount.SLCustomerAccountID
-- from AnB_Live.SLCustomerAccount
-- JOIN AnB_Live.SLCustomerLocation
-- 	ON AnB_Live.SLCustomerAccount.SLCustomerAccountID = AnB_Live.SLCustomerLocation.SLCustomerAccountID
--     
-- Union
-- Select * from	(
-- 		
--        Select * FROM 
--         AnB_Live.CustDeliveryAddress
--         JOIN AnB_Live.SLCustomerAccount
-- 			ON AnB_Live.CustDeliveryAddress.CustomerID = AnB_Live.SLCustomerAccount.SLCustomerAccountID
-- 		) Gezz
        
-- SELECT * 
-- 
-- from AnB_Live.SLCustomerAccount
-- JOIN AnB_Live.SLCustomerLocation
-- 	ON AnB_Live.SLCustomerAccount.SLCustomerAccountID = AnB_Live.SLCustomerLocation.SLCustomerAccountID
--     
--     Left Join AnB_Live.CustDeliveryAddress
-- 		ON AnB_Live.CustDeliveryAddress.CustomerID = AnB_Live.SLCustomerAccount.SLCustomerAccountID
--     Order by AnB_Live.SLCustomerAccount.SLCUstomerAccountID

-- attach account details to the invoice addresses.
SELECT *
FROM AnB_Live.SLCustomerAccount
JOIN AnB_Live.SLCustomerLocation
ON AnB_Live.SLCustomerAccount.SLCustomerAccountID = AnB_Live.SLCustomerLocation.SLCustomerAccountID

-- attach account details to the delivery addresses.
SELECT *
FROM AnB_Live.CustDeliveryAddress
LEFT JOIN AnB_Live.SLCustomerAccount
ON AnB_Live.CustDeliveryAddress.CustomerID = AnB_Live.SLCustomerAccount.SLCustomerAccountID

-- attach account details to the delivery addresses WITH specific columns.
SELECT AnB_Live.CustDeliveryAddress.CustomerID as CustomerID, AnB_Live.SLCustomerAccount.CustomerAccountNumber as CustomerAccountNumber, AnB_Live.SLCustomerAccount.CustomerAccountName as CustomerAccountName, AnB_Live.CustDeliveryAddress.PostalName as PostalName, AnB_Live.CustDeliveryAddress.AddressLine1 as AddressLine1, AnB_Live.CustDeliveryAddress.AddressLine2 as AddressLine2, AnB_Live.CustDeliveryAddress.AddressLine3 as AddressLine3, AnB_Live.CustDeliveryAddress.AddressLine4 as AddressLine4, AnB_Live.CustDeliveryAddress.City as City, AnB_Live.CustDeliveryAddress.County as County, AnB_Live.CustDeliveryAddress.PostCode as PostCode, AnB_Live.CustDeliveryAddress.CountryCodeID as CountryCodeID, AnB_Live.SLCustomerAccount.AnalysisCode2 as AnalysisCode2
FROM AnB_Live.CustDeliveryAddress
LEFT JOIN AnB_Live.SLCustomerAccount
ON AnB_Live.CustDeliveryAddress.CustomerID = AnB_Live.SLCustomerAccount.SLCustomerAccountID

-- attach account details to the invoice addresses WITH specific columns.
SELECT AnB_Live.SLCustomerAccount.SLCustomerAccountID as CustomerID, AnB_Live.SLCustomerAccount.CustomerAccountNumber as CustomerAccountNumber, AnB_Live.SLCustomerAccount.CustomerAccountName as CustomerAccountName, AnB_Live.SLCustomerAccount.CustomerAccountName as PostalName, AnB_Live.SLCustomerLocation.AddressLine1 as AddressLine1, AnB_Live.SLCustomerLocation.AddressLine2 as AddressLine2, AnB_Live.SLCustomerLocation.AddressLine3 as AddressLine3, AnB_Live.SLCustomerLocation.AddressLine4 as AddressLine4, AnB_Live.SLCustomerLocation.City as City, AnB_Live.SLCustomerLocation.County as County, AnB_Live.SLCustomerLocation.PostCode as PostCode, AnB_Live.SLCustomerLocation.SYSCountryCodeID as CountryCodeID, AnB_Live.SLCustomerAccount.AnalysisCode2 as AnalysisCode2
FROM AnB_Live.SLCustomerAccount
JOIN AnB_Live.SLCustomerLocation
ON AnB_Live.SLCustomerAccount.SLCustomerAccountID = AnB_Live.SLCustomerLocation.SLCustomerAccountID

-- Creating the Temporary Table from the join of CustDeliveryAddress and SLCustomerAccount to store before merge
CREATE TEMPORARY TABLE AnB_Live.CustDeliveryAddressSLCustomerAccount
SELECT AnB_Live.CustDeliveryAddress.CustomerID as CustomerID, AnB_Live.SLCustomerAccount.CustomerAccountNumber as CustomerAccountNumber, AnB_Live.SLCustomerAccount.CustomerAccountName as CustomerAccountName, AnB_Live.CustDeliveryAddress.PostalName as PostalName, AnB_Live.CustDeliveryAddress.AddressLine1 as AddressLine1, AnB_Live.CustDeliveryAddress.AddressLine2 as AddressLine2, AnB_Live.CustDeliveryAddress.AddressLine3 as AddressLine3, AnB_Live.CustDeliveryAddress.AddressLine4 as AddressLine4, AnB_Live.CustDeliveryAddress.City as City, AnB_Live.CustDeliveryAddress.County as County, AnB_Live.CustDeliveryAddress.PostCode as PostCode, AnB_Live.CustDeliveryAddress.CountryCodeID as CountryCodeID, AnB_Live.SLCustomerAccount.AnalysisCode2 as AnalysisCode2
FROM AnB_Live.CustDeliveryAddress
LEFT JOIN AnB_Live.SLCustomerAccount
ON AnB_Live.CustDeliveryAddress.CustomerID = AnB_Live.SLCustomerAccount.SLCustomerAccountID

-- test temp table
SELECT *
FROM AnB_Live.CustDeliveryAddressSLCustomerAccount

-- Creating the Temporary Table from the join of SLCustomerAccount and SLCustomerLocation to store before merge
CREATE TEMPORARY TABLE AnB_Live.SLCustomerAccountSLCustomerLocation
SELECT AnB_Live.SLCustomerAccount.SLCustomerAccountID as CustomerID, AnB_Live.SLCustomerAccount.CustomerAccountNumber as CustomerAccountNumber, AnB_Live.SLCustomerAccount.CustomerAccountName as CustomerAccountName, AnB_Live.SLCustomerAccount.CustomerAccountName as PostalName, AnB_Live.SLCustomerLocation.AddressLine1 as AddressLine1, AnB_Live.SLCustomerLocation.AddressLine2 as AddressLine2, AnB_Live.SLCustomerLocation.AddressLine3 as AddressLine3, AnB_Live.SLCustomerLocation.AddressLine4 as AddressLine4, AnB_Live.SLCustomerLocation.City as City, AnB_Live.SLCustomerLocation.County as County, AnB_Live.SLCustomerLocation.PostCode as PostCode, AnB_Live.SLCustomerLocation.SYSCountryCodeID as CountryCodeID, AnB_Live.SLCustomerAccount.AnalysisCode2 as AnalysisCode2
FROM AnB_Live.SLCustomerAccount
JOIN AnB_Live.SLCustomerLocation
ON AnB_Live.SLCustomerAccount.SLCustomerAccountID = AnB_Live.SLCustomerLocation.SLCustomerAccountID

-- test temp table
SELECT *
FROM AnB_Live.SLCustomerAccountSLCustomerLocation

-- now for the merge!

CREATE TABLE AnB_Live.DeliveryAddress
SELECT *
FROM AnB_Live.CustDeliveryAddressSLCustomerAccount
UNION ALL
SELECT *
FROM AnB_Live.SLCustomerAccountSLCustomerLocation

-- test the merge

SELECT *
FROM AnB_Live.DeliveryAddress

-- Drop the temp tables now they have been used.

DROP TEMPORARY TABLE IF EXISTS AnB_Live.CustDeliveryAddressSLCustomerAccount, AnB_Live.SLCustomerAccountSLCustomerLocation

-- now to remove the non UK (CountryCode = 13) accounts

DELETE FROM AnB_Live.DeliveryAddress
WHERE AnB_Live.DeliveryAddress.CountryCodeID != 13

-- not to remove the closed (AnalysisCode2 != 'closed') accounts

DELETE FROM AnB_Live.DeliveryAddress
WHERE AnB_Live.DeliveryAddress.AnalysisCode2 = 'closed'

-- add lat and lng columns
ALTER TABLE AnB_Live.DeliveryAddress ADD lat DECIMAL(10, 8) NOT NULL;
ALTER TABLE AnB_Live.DeliveryAddress ADD lng DECIMAL(11, 8) NOT NULL;

-- add uni_id
ALTER TABLE AnB_Live.DeliveryAddress ADD uni_id INT PRIMARY KEY AUTO_INCREMENT FIRST;


select uni_id, PostalName, lat, lng from AnB_Live.DeliveryAddress where lat = 0 or lng = 0 order by PostalName