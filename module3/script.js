/*jslint devel: true */


//Calculates the median of entered values
function calcMedian (numArray) {
    numArray.sort(function(a,b){
    return a-b;
  });

  if(numArray.length ===0) return 0

  var half = Math.floor(numArray.length / 2);

  if (numArray.length % 2)
    return (numArray[half]).toFied(2);
  else
    return ((numArray[half - 1] + numArray[half]) / 2.0).toFixed(2);
}

//Calculates the most common value of the entered values
function calcMode(numArray) {
    var modes = [], count = [], i, number, maxIndex = 0;
 
    for (i = 0; i < numArray.length; i += 1) {
        number = numArray[i];
        count[number] = (count[number] || 0) + 1;
        if (count[number] > maxIndex) {
            maxIndex = count[number];
        }
    }
 
    for (i in count)
        if (count.hasOwnProperty(i)) {
            if (count[i] === maxIndex) {
                modes.push(Number(i));
            }
        }
 
    return modes;
}
//Calculates the standard deviation 
function calcStdDev(numArray) {
    return (Math.sqrt(calcVariance(numArray))).toFixed(2);
}

//Calculates the sum of all teh values
function calcSum(numArray) {
    var sum = 0;
    for(i = 0; i < numArray.length; i++){
        sum += parseInt(numArray[i].valueOf());
    }
    return (sum).toFixed(2);
}

//Calculates the mean of entered values
function calcMean(numArray) {
    var mean = calcSum(numArray)/(parseInt(numArray.length));
    
    return (mean.valueOf()).toFixed(2);    
}

//Calculate the variance
function calcVariance(numArray) {
    var sqrMean = 0;
    for (var i = 0; i < numArray.length; i++) {
        var dif = calcMean(numArray) - numArray[i];
        sqrMean += (dif * dif);
    }
    return (sqrMean / (numArray.length - 1)).toFixed(2);
}

//Finds the max of the value
function findMax(numArray) {
    var sortedArray = numArray.sort();
    var max = Math.max.apply(null, sortedArray);
    
    return max.toFixed(2);
}

//Finds the min of the values
function findMin(numArray) {
    var sortedArray = numArray.sort();
    var min = Math.min.apply(null, sortedArray);
    return min.toFixed(2);
}

//Creates the array and performs the other functions
function performStatistics() {
    var userInput = document.getElementById("user-input").value;
    var numArray = userInput.split(" ");
    if(isNaN(userInput[0])){
        alert("Please enter in a valid number.");
    }else{
        document.getElementById("max").value = findMax(numArray);
        document.getElementById("mean").value = calcMean(numArray);
        document.getElementById("median").value = calcMedian(numArray);
        document.getElementById("min").value = findMin(numArray);
        document.getElementById("mode").value = calcMode(numArray);
        document.getElementById("stdDev").value = calcStdDev(numArray);
        document.getElementById("sum").value = calcVariance(numArray);
        document.getElementById("variance").value = calcSum(numArray);
        
    }
}