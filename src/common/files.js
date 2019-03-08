"use strict";

const fs = require("fs");
const nodePath = require("path");
const util = require("./util");

const gitletDir = dir => {
  if (!fs.existsSync(dir)) return undefined;

  const potentialConfigFile = nodePath.join(dir, "config");
  const potentialGitletPath = nodePath.join(dir, ".gitlet");

  if (
    fs.existsSync(potentialConfigFile) &&
    fs.statSync(potentialConfigFile).isFile() &&
    read(potentialConfigFile).match(/\[core\]/)
  ) {
    return dir;
  } else if (fs.existsSync(potentialGitletPath)) {
    return potentialGitletPath;
  } else if (dir !== "/") {
    return gitletDir(nodePath.join(dir, ".."));
  } else {
    return undefined;
  }
};

const gitletPath = path => {
  const gDir = gitletDir(process.cwd());
  if (gDir !== undefined) {
    return nodePath.join(gDir, path || "");
  }
};

const inRepo = () => gitletPath() !== undefined;

const writeFilesFromTree = (tree, prefix) => {
  Object.keys(tree).forEach(name => {
    const path = nodePath.join(prefix, name);
    if (util.isString(tree[name])) {
      fs.writeFileSync(path, tree[name]);
    } else {
      if (!fs.existsSync(path)) {
        fs.mkdirSync(path, "777");
      }
      writeFilesFromTree(tree[name], path);
    }
  });
};

module.exports = {
  gitletPath,
  inRepo,
  writeFilesFromTree
};
