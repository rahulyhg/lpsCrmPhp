/* 
 * Author S. Brinta <brrinta@gmail.com>
 * Email: i@brinta.me
 * Web: https://brinta.me
 * Do not edit file without permission of author
 * All right reserved by S. Brinta <brrinta@gmail.com>
 * Created on : Aug 10, 2018 , 8:53:53 AM
 */
/**
 * Author:  S. Brinta <brrinta@gmail.com>
 * Created: Aug 10, 2018
 */

SELECT t1.*
FROM `orders` t1
LEFT JOIN `prospects` t2 ON t2.`contactID`= t1.`contactID`
WHERE t2.`contactID` IS NULL;

UPDATE orders
INNER JOIN prospects ON prospects.contactID = orders.contactID
SET orders.prospectsID =prospects.id
WHERE orders.prospectsID='';


DELETE FROM userLog where DATEDIFF(NOW(), activityTime)>15;


SELECT * FROM `labor_home`.`orders` LEFT JOIN `labor_crm`.`orders` ON `labor_home`.`orders`.`bcontact_id`= `labor_crm`.`orders`.`contactID` WHERE 
`labor_crm`.`orders`.`contactID` is NULL;


UPDATE proDocProspects
INNER JOIN proDocOrders ON proDocProspects.contactID = proDocOrders.contactID
SET proDocProspects.ordered = '1';
UPDATE fictitious
INNER JOIN fictitiousOrders ON fictitiousOrders.contactID = fictitious.contactID
SET fictitious.ordered = '1';