// Select the node that will be observed for mutations
const targetNode = document.querySelector('.form-sections[data-section="6"]');

// Options for the observer (which mutations to observe)
const config = { attributes: true, attributeFilter: ["style"] };

// Callback function to execute when mutations are observed
const callback = function (mutationsList) {
  console.log(mutationsList);
  // Use traditional 'for loops' for IE 11
  for (const mutation of mutationsList) {
    console.log(mutation);
    if (mutation.type === "attributes") {
      console.log("The " + mutation.attributeName + " attribute was modified.");
    }
  }
};

// Create an observer instance linked to the callback function
const observer = new MutationObserver(callback);
// Start observing the target node for configured mutations
observer.observe(targetNode, config);

// Later, you can stop observing
observer.disconnect();
