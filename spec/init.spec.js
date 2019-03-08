"use strict";

const fs = require("fs");
const g = require("../src");
const testUtil = require("./test-util");

describe("init", () => {
  beforeEach(testUtil.initTestDataDir);

  it("should create .gitlet/ and all required dirs", () => {
    g.init();

    expect(
      fs.existsSync(__dirname + "/testData/repo1/.gitlet/objects/")
    ).toEqual(true);
    expect(fs.existsSync(__dirname + "/testData/repo1/.gitlet/refs/")).toEqual(
      true
    );
    expect(
      fs.existsSync(__dirname + "/testData/repo1/.gitlet/refs/heads/")
    ).toEqual(true);
    testUtil.expectFile(
      __dirname + "/testData/repo1/.gitlet/HEAD",
      "ref: refs/heads/master\n"
    );
    testUtil.expectFile(
      __dirname + "/testData/repo1/.gitlet/config",
      "[core]\n  bare = false\n"
    );
  });

  it("should not change anything if init run twice", () => {
    g.init();
    g.init();

    expect(
      fs.existsSync(__dirname + "/testData/repo1/.gitlet/objects/")
    ).toEqual(true);
    expect(fs.existsSync(__dirname + "/testData/repo1/.gitlet/refs/")).toEqual(
      true
    );
    expect(
      fs.existsSync(__dirname + "/testData/repo1/.gitlet/refs/heads/")
    ).toEqual(true);
    testUtil.expectFile(
      __dirname + "/testData/repo1/.gitlet/HEAD",
      "ref: refs/heads/master\n"
    );
    testUtil.expectFile(
      __dirname + "/testData/repo1/.gitlet/config",
      "[core]\n  bare = false\n"
    );
  });

  it("should not crash when config is a directory", () => {
    const dir = __dirname + "/testData/repo1/";
    fs.mkdirSync(dir + "config");
    g.init();
  });

  describe("bare repos", () => {
    it("should put all gitlet files and folders in root if specify bare", () => {
      g.init({ bare: true });

      expect(fs.existsSync(__dirname + "/testData/repo1/objects/")).toEqual(
        true
      );
      expect(fs.existsSync(__dirname + "/testData/repo1/refs/")).toEqual(true);
      expect(fs.existsSync(__dirname + "/testData/repo1/refs/heads/")).toEqual(
        true
      );
      testUtil.expectFile(
        __dirname + "/testData/repo1/HEAD",
        "ref: refs/heads/master\n"
      );
      testUtil.expectFile(
        __dirname + "/testData/repo1/config",
        "[core]\n  bare = true\n"
      );
    });
  });
});
