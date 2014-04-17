package ee.ut.mt.webphp.tests;

import junit.framework.Test;
import junit.framework.TestSuite;

public class RegixTests {

	public static Test suite() {
		TestSuite suite = new TestSuite();
		suite.addTestSuite(MyPlanFirefoxTest.class);
		return suite;
	}

	public static void main(String[] args) {
		junit.textui.TestRunner.run(suite());
	}
}