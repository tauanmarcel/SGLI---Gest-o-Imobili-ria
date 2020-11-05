export const maskPhone = function maskPhone(phone){
   const currentPhone = phone;
   let parsePhone;

   if(currentPhone.length === 9) {

       let part1 = currentPhone.slice(0,5);
       let part2 = currentPhone.slice(5,9);
       parsePhone = `(00) ${part1}-${part2}`;

   } else if(currentPhone.length === 10) {

        let ddd = currentPhone.slice(0,2);
        let part1 = currentPhone.slice(2,6);
        let part2 = currentPhone.slice(6,10);
        parsePhone = `(${ddd}) ${part1}-${part2}`;

    } else if(currentPhone.length === 11) {

       let ddd = currentPhone.slice(0,2);
       let nine = currentPhone.slice(2,3);
       let part1 = currentPhone.slice(3,7);
       let part2 = currentPhone.slice(7,11);
       parsePhone = `(${ddd}) ${nine} ${part1}-${part2}`;
   } 

   return parsePhone;
}