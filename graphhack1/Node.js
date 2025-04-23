import { execSync } from "child_process";
import fs from "fs";

const makeCommit = (date) => {
    // Create a random message
    const message = `Commit for ${date}`;

    // Write a file to make a change in the repository
    fs.writeFileSync("temp.txt", message);

    // Use Git commands to commit on a specific date
    execSync("git add .");
    execSync(`git commit -m "${message}" --date="${date}" || true`); // Avoid errors if no branch exists yet
};

// Example of generating dates for the last 30 days
const days = 100; // Change this number to go further back
for (let i = 0; i < days; i++) {
    const date = new Date();
    date.setDate(date.getDate() - i); // Go back by i days
    makeCommit(date.toISOString());
}

// Push the changes to GitHub
execSync("git push origin master"); // Push changes to the master branch (or replace 'master' with your actual branch name)

// Clean up
fs.unlinkSync("temp.txt");